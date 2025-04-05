<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TournotResource\Pages;
use App\Filament\Resources\TournotResource\RelationManagers;
use App\Models\Tournot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TournotResource extends Resource
{
    protected static ?string $model = Tournot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Optionally you can keep the hidden field for UI purposes


                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('message')
                    ->label('Message')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Link')
                    ->url()
                    ->nullable(),

                Forms\Components\Radio::make('audience')
                    ->label('Send To')
                    ->options([
                        'all' => 'All Students',
                        'specific' => 'Specific Student',
                    ])
                    ->live()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state === 'specific') {
                            $set('dep_id', null); // Clear dep_id when 'Specific Department' is selected
                        } else {
                            $set('dep_id', Auth::guard('web')->user()?->dep_id); // Set dep_id to the user's department ID
                        }
                    })
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name', function (Builder $query) {
                        // Get the guard from Filament configuration (defaulting to 'web' if not set)

                        return $query->where('dep_id', Auth::guard('web')->user()?->dep_id)->where('id', '!=', Auth::guard('web')->user()?->id);
                    })
                    ->visible(fn(Forms\Get $get) => $get('audience') === 'specific')
                    ->required(fn(Forms\Get $get) => $get('audience') === 'specific'),

                Forms\Components\Hidden::make('dep_id'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->filters([
                QueryBuilder::make()
                    ->label('Department Filter') // Optional: Give the filter a name
                    ->query(function (Builder $query) {
                        // Apply the filtering logic based on dep_id
                        if ($user = Auth::guard('web')->user()) {
                            $query->where('dep_id', $user->dep_id);
                        } else {
                            $query->whereRaw('1 = 0');
                        }
                    })
            ], layout: FiltersLayout::AboveContent)
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('link')
                    ->label('Link')
                    ->url(fn(Tournot $record) => $record->link)
                    ->sortable()
                    ->searchable(),

                BooleanColumn::make('is_read')
                    ->label('Is Read')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // This method forces the admin_id to be set before creating a record.
    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->guard('web')->user();
        if ($user) {
            $data['dep_id'] = $user->dep_id;
        }
        return $data;
    }
    public function getDepartmentNotifications()
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            return Tournot::where('dep_id', $user->dep_id)->get();
        }

        return collect();
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTournots::route('/'),
            'create' => Pages\CreateTournot::route('/create'),
            'view'   => Pages\ViewTournot::route('/{record}'),
            'edit'   => Pages\EditTournot::route('/{record}/edit'),
        ];
    }
}
