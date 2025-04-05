<?php

namespace App\Filament\Resources\TournotResource\Pages;

use App\Filament\Resources\TournotResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTournot extends ViewRecord
{
    protected static string $resource = TournotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
