<?php

namespace Tonystore\LaravelTournaments\Models;

use Exception;
use DDZobov\PivotSoftDeletes\Model;

class Tournament extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'rounds',
        'participants_count',
        'tournament_type',
        'group_stage',
        'admin_id',
        'division_id',
        'promotions_division_id',
        'descents_division_id',
        'promotions_count',
        'descents_count',
        'status',
    ];
    public $fixture_model;
    public $result_model;
    public $division_model;
    public $team_model;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fixture_model = config('laravel-tournaments.models.fixture');
        $this->result_model = config('laravel-tournaments.models.result');
        $this->team_model = config('laravel-tournaments.models.team');
        $this->division_model = config('laravel-tournaments.models.division');
        $this->setTable(config('laravel-tournaments.models.tournaments'));
    }
    public function teams()
    {
        return $this->belongsToMany(
            config('laravel-tournaments.models.team'),
            config('laravel-tournaments.table_names.team_tournament')
        )->withSoftDeletes();
    }
    public function fixtures()
    {
        return $this->hasMany($this->fixture_model)
            ->whereIn('local_id', $this->teams->pluck('id'))
            ->whereIn('visitante_id', $this->teams->pluck('id'))
            ->addSelect([
                'goals_local_team' =>  $this->result_model::select('goals')
                    ->whereColumn('fixture_id', 'fixtures.id')
                    ->where('type', 'local')
                    ->orderByDesc('created_at')
                    ->limit(1)
            ])
            ->addSelect([
                'goals_visiting_team' =>  $this->result_model::select('goals')
                    ->whereColumn('fixture_id', 'fixtures.id')
                    ->where('type', 'visitor')
                    ->orderByDesc('created_at')
                    ->limit(1)
            ]);
    }
    public function division()
    {
        return $this->belongsTo($this->division_model);
    }
    public function addTeam($team)
    {
        if (!is_a($team, $this->team_model)) {
            throw new Exception("The model is not an instance of Team.");
        } else {
            if ($this->isTeamDelete($team)) {
                $this->teams()->restore([$team->id]);
                return $this->refresh();
            }
            // $this->updatedPivotTeam($team->id);

            $this->teams()->attach($team);
            return $this->refresh();
        }
    }
    public function deleteTeam($team)
    {
        if (!is_a($team, $this->team_model)) {
            throw new Exception("The model is not an instance of Team.");
        } else {
            $this->updatedPivotTeam($team->id, now());
            return $this->refresh();
        }
    }
    public function updatedPivotTeam($id, $time = null)
    {
        $this->teams()
            ->updateExistingPivot(
                $id,
                [
                    'deleted_at' => $time
                ]
            );
    }
    public function hasTeam($team)
    {
        return  $this->teams()
            ->where($team->getQualifiedKeyName(), $team->id)
            ->exists();
    }
    public function isTeamDelete($team)
    {
        return  $this->teams()
            ->where($team->getQualifiedKeyName(), $team->id)
            ->withTrashedPivots()
            ->exists();
    }
    public function findTeam($team)
    {
        if (!is_a($team, $this->team_model)) {
            throw new Exception("The model is not an instance of Team.");
        } else {
        }
        return $$this->teams()
            ->where($team->getQualifiedKeyName(), $team->id)
            ->withTrashedPivots()
            ->first();
    }
}
