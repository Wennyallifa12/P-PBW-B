<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKegiatans extends ListRecords
{
    protected static string $resource = KegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
