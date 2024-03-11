<?php

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListOrganisations extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            //OrganisationResource\Widgets\OrganisationOverview::class
        ];
    }
}
