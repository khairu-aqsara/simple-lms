<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\RelationManagers\CourseCategoriesRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\OrganisationResource\Pages;
use App\Filament\Resources\OrganisationResource\Widgets\OrganisationOverview;
use App\Filament\Resources\UserResource\RelationManagers\MembersRelationManager;
use App\Models\Organisation;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Organisation Name')->required(),
                        Forms\Components\Checkbox::make('is_active')->label('Active')
                            ->required()->default(false)
                            ->helperText('When unchecked, all users belongs to this organisation will not be able to user the platform')
                            ->accepted()
                    ])
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Organisation Name')->searchable(),
                Tables\Columns\TextColumn::make('members_count')->counts('members'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->since()

            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')->label('Activated Organisations')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
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
            CourseCategoriesRelationManager::class,
            CoursesRelationManager::class,
            MembersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisations::route('/'),
            'create' => Pages\CreateOrganisation::route('/create'),
            'edit' => Pages\EditOrganisation::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
          OrganisationOverview::class
        ];
    }
}
