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
                TextInput::make('name')
                ->label('نام')
                ->required(),

                TextInput::make('email')
                ->label('ایمیل')
                ->email(),

                // Select::make('group_id')->relationship('group', 'name')->nullable(),
                Select::make('group_id')
                    ->label('گروه')
                    ->options(Group::all()->pluck('name', 'id'))
                    ->searchable(),

                Repeater::make('phoneNumbers')
                    ->label('شماره ها')
                    ->relationship('phoneNumbers')
                    ->schema([
                        TextInput::make('number')
                        ->label('شماره')
                        ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('نام')
                ->sortable()->searchable(),
                TextColumn::make('email')
                ->label('ایمیل')
                ->sortable()->searchable(),
                TextColumn::make('group.name')
                ->label('گروه')->sortable()->searchable(),
                TextColumn::make('phoneNumbers.number')
                ->label('شماره ها')->sortable(),
            ])
            ->filters([
                //SelectFilter: این فیلتر به شما اجازه می‌دهد که مخاطبین را بر اساس گروه آن‌ها فیلتر کنید.
                SelectFilter::make('group')
                    ->relationship('group', 'name')
                    ->label('گروه')
                    ->searchable(),
                //Custom Filter: این فیلتر به شما اجازه می‌دهد که مخاطبینی که حداقل یک شماره تلفن دارند را فیلتر کنید.
                Filter::make('has_number')
                    ->label('شماره تلفن')
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

    public static function getModelLabel(): string
    {
        return __('مخاطب');
    }
    public static function getPluralModelLabel(): string
    {
        return __('مخاطبین');
    }
}
