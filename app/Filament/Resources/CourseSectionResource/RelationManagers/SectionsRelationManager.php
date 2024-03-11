<?php

namespace App\Filament\Resources\CourseSectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Section Image')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('course-section-image')
                    ->visibility('private')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('scorm_file')
                    ->label('Scorm File')
                    ->disk('public')
                    ->directory('course-section-scorm')
                    ->visibility('private')
                    ->acceptedFileTypes(['application/zip','application/octet-stream','application/x-zip-compressed','multipart/x-zip'])
                    ->helperText('Scorm file must be in Zip compressed file format')
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => (string) str(md5($file->getClientOriginalName())).'.'.$file->getClientOriginalExtension()
                    )->downloadable()->deletable(),

                Forms\Components\TextInput::make('scorm_version')
                    ->label('Scorm Version')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('scorm_version')->searchable()->sortable(),
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
