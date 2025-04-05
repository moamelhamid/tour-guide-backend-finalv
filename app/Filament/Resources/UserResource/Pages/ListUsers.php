<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Imports\UsersImport;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('UsersImport')
                ->label('Import Student')
                ->icon('heroicon-o-arrow-up-on-square')
                ->form([
                    FileUpload::make('attachment')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Retrieve the authenticated user's department ID
                    $dep_id = Auth::guard('web')->user()->dep_id;

                    // Get the uploaded file's path
                    $file = public_path('storage/' . $data['attachment']);

                    // Perform the import, passing the department ID to the import class
                    Excel::import(new UsersImport($dep_id), $file);

                    // Send a success notification
                    Notification::make()
                        ->title('Students Imported')
                        ->success()
                        ->send();
                }),

            Action::make('Export')
                ->label('Export to Excel')
                ->icon('heroicon-s-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new UsersExport, 'users.xlsx');
                }),
        ];
    }
}
