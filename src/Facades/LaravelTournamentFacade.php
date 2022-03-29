<?php

namespace Tonystore\LaravelTournaments\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelTournamentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelTournament';
    }
}
