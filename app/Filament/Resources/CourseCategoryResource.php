<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\Pages;
use App\Filament\Resources\CourseCategoryResource\RelationManagers;
use App\Models\CourseCategory;
use App\Models\Organisation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseCategoryResource extends Resource
{
    protected static ?string $model = CourseCategory::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-backspace';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                ->schema([
                    Forms\Components\Select::make('organisation_id')->label('Belongs to Organisation')
                        ->required()->options(Organisation::all()->pluck('name','id'))->native(false)->searchable(),
                    Forms\Components\TextInput::make('title')->label('Category Title')
                        ->required(),
                    Forms\Components\MarkdownEditor::make('description')
                        ->fileAttachmentsDirectory('categories')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsVisibility('private')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Category Title')->searchable(),
                Tables\Columns\TextColumn::make('organisation.name')->label('Belongs To')->searchable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organisation_id')->label('Organisation')
                    ->options(Organisation::all()->pluck('name','id'))->native(false)->searchable()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseCategories::route('/'),
            'create' => Pages\CreateCourseCategory::route('/create'),
            'edit' => Pages\EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
