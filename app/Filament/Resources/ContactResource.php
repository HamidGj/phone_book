<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                TextInput::make('email')->email(),

                // Select::make('group_id')->relationship('group', 'name')->nullable(),
                Select::make('group_id')
                    ->label('Group')
                    ->options(Group::all()->pluck('name', 'id'))
                    ->searchable(),

                Repeater::make('phoneNumbers')
                    ->relationship('phoneNumbers')
                    ->schema([
                        TextInput::make('number')->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('group.name')->label('Group')->sortable()->searchable(),
                TextColumn::make('phoneNumbers.number')->label('Phone Numbers')->sortable(),
            ])
            ->filters([
                //SelectFilter: این فیلتر به شما اجازه می‌دهد که مخاطبین را بر اساس گروه آن‌ها فیلتر کنید.
                SelectFilter::make('group')
                    ->relationship('group', 'name')
                    ->label('Group')
                    ->searchable(),
                //Custom Filter: این فیلتر به شما اجازه می‌دهد که مخاطبینی که حداقل یک شماره تلفن دارند را فیلتر کنید.
                Filter::make('has_number')
                    ->label('Has Phone Number')
                    ->query(fn (Builder $query): Builder => $query->whereHas('phoneNumbers')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
