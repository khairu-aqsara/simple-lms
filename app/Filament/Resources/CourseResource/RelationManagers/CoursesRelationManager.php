<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\CourseCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')->label('Category')
                    ->options(fn(Forms\Get $get) => CourseCategory::where('organisation_id', $this->getOwnerRecord()->getKey())->pluck('title','id'))
                    ->native(false)->searchable()->columnSpanFull(),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Course Title'),
                        Forms\Components\TextInput::make('shortname')->label('Course Shortname'),
                        Forms\Components\RichEditor::make('description')->label('Description')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('private')
                            ->fileAttachmentsDirectory('courses')
                            ->required()
                    ]),
                Forms\Components\FileUpload::make('image')->disk('public')
                    ->image()
                    ->imageEditorAspectRatios([
                        null,'16:9','4:3','1:1'
                    ])
                    ->imageEditor()
                    ->preserveFilenames()
                    ->directory('courses-images')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->columnSpanFull()->default(false)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\IconColumn::make('is_published')->label('Published'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('shortname')->label('Shortname')->searchable(),
                Tables\Columns\TextColumn::make('category.title')->label('Category'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
