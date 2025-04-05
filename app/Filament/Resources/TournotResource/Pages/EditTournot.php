<?php

namespace App\Filament\Resources\TournotResource\Pages;

use App\Filament\Resources\TournotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTournot extends EditRecord
{
    protected static string $resource = TournotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
