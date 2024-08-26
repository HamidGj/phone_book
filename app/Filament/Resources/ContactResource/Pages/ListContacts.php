<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                ->use(\App\Imports\ContactsImport::class),
                ExportAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
