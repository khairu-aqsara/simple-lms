<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Users';

    public function form(Form $form): Form
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
                Forms\Components\Select::make('role')
                    ->native(false)
                    ->options([
                        UserRole::ADMINISTRATOR->value => 'Administrator',
                        UserRole::TEACHER->value => 'Teacher/Instructor',
                        UserRole::LEARNER->value => 'Learner/Student'
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
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
                Tables\Columns\TextColumn::make('created_at')->label('Created At')
                    ->dateTime('d/m/Y H:i')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->options(array_column(\App\Enums\UserRole::cases(), 'value', 'value'))->native(false)
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
