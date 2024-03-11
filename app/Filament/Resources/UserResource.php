<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Organisation;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Full Name')
                    ->prefixIcon('heroicon-m-user')
                    ->required(),
                Forms\Components\TextInput::make('email')->label('Email Address')->required()
                    ->prefixIcon('heroicon-m-envelope')
                    ->email(),
                Forms\Components\TextInput::make('password')->label('Password')
                    ->prefixIcon('heroicon-m-key')
                    ->prefixIconColor('success')
                    ->password()->revealable(),
                Forms\Components\Section::make('Organisation')
                    ->columns(2)
                    ->description('Organisation related information')
                    ->schema([
                        Forms\Components\Select::make('organisation_id')
                            ->label('Organisation')
                            ->native(false)
                            ->searchable()
                            ->options(Organisation::all()->pluck('name','id')),
                        Forms\Components\Select::make('role')
                            ->native(false)
                            ->options([
                                UserRole::ADMINISTRATOR->value => 'Administrator',
                                UserRole::TEACHER->value => 'Teacher/Instructor',
                                UserRole::LEARNER->value => 'Learner/Student'
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        UserRole::SYSTEM->value => 'gray',
                        UserRole::ADMINISTRATOR->value => 'danger',
                        UserRole::TEACHER->value => 'warning',
                        UserRole::LEARNER->value => 'success'
                    }),
                Tables\Columns\TextColumn::make('name')->label('Full Name')->searchable()->icon('heroicon-o-user-circle'),
                Tables\Columns\TextColumn::make('email')->label('Email Address')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('organisation.name')
                    ->label('Organisation')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')
                    ->dateTime('d/m/Y H:i')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organisation_id')
                    ->label('Organisation')
                    ->options(Organisation::where('is_active', true)
                            ->get()->pluck('name','id'))
                    ->native(false)
                    ->searchable()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
