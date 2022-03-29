<?php


return [
    'models' => [
        'division' => \Tonystore\LaravelTournaments\Models\Division::class,
        'team' => \Tonystore\LaravelTournaments\Models\Team::class,
        'tournament' => \Tonystore\LaravelTournaments\Models\Tournament::class,
        'fixture' => \Tonystore\LaravelTournaments\Models\Fixture::class,
        'result' => \Tonystore\LaravelTournaments\Models\Result::class,
    ],
    'table_names' => [
        'tournaments' => 'tournaments',
        'divisions' => 'divisions',
        'teams' => 'teams',
        'team_tournament' => 'team_tournament',
        'fixtures' => 'fixtures',
        'results' => 'results',
        'users' => 'users',
    ]
];
