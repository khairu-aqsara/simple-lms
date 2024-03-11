<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseSectionResource\Pages;
use App\Models\Course;
use App\Models\CourseSection;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CourseSectionResource extends Resource
{
    protected static ?string $model = CourseSection::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')->label('Course')
                    ->required()->options(Course::pluck('title','id'))
                    ->native(false)
                    ->searchable()
                    ->columnSpanFull(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('scorm_version')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('scorm_version')->options([
                    '1.2' => 'Scorm 1.2',
                    '2004' => 'Scorm 2004'
                ])->native(false),
                Tables\Filters\SelectFilter::make('course_id')->label('Course')
                    ->options(Course::pluck('title', 'id'))->native(false)->searchable()
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
            'index' => Pages\ListCourseSections::route('/'),
            'create' => Pages\CreateCourseSection::route('/create'),
            'edit' => Pages\EditCourseSection::route('/{record}/edit'),
        ];
    }
}
