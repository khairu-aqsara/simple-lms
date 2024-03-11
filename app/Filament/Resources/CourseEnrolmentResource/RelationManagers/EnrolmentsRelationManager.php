<?php

namespace App\Filament\Resources\CourseEnrolmentResource\RelationManagers;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrolmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrolments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->label('User')
                    ->options(User::where('organisation_id', $this->getOwnerRecord()->organisation_id)->pluck('name','id'))
                    ->native(false)->searchable()->required()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('user.role')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        UserRole::SYSTEM->value => 'gray',
                        UserRole::ADMINISTRATOR->value => 'danger',
                        UserRole::TEACHER->value => 'warning',
                        UserRole::LEARNER->value => 'success'
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrolled At')
                    ->dateTime('d/m/Y H:i')->sortable()
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
