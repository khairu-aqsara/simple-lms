<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrolmentResource\RelationManagers\EnrolmentsRelationManager;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseSectionResource\RelationManagers\SectionsRelationManager;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Organisation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organisation_id')
                    ->label('Organisation')->required()->options(Organisation::all()->pluck('name','id'))
                    ->native(false)->searchable()->reactive(),
                Forms\Components\Select::make('category_id')->label('Category')
                    ->options(fn(Forms\Get $get) => CourseCategory::where('organisation_id', $get('organisation_id'))->pluck('title','id'))
                    ->disabled(fn(Forms\Get $get): bool =>  !filled($get('organisation_id'))),
                    //->native(false)->searchable(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_published')->label('Published'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('shortname')->label('Shortname')->searchable(),
                Tables\Columns\TextColumn::make('organisation.name')->label('Organisation'),
                Tables\Columns\TextColumn::make('category.title')->label('Category'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organisation_id')
                    ->label('Organisation')->options(Organisation::pluck('name', 'id'))->native(false)->searchable(),
                Tables\Filters\SelectFilter::make('category_id')->label('Category')
                    ->options(CourseCategory::pluck('title', 'id'))->native(false)->searchable()
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
            SectionsRelationManager::class,
            EnrolmentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
