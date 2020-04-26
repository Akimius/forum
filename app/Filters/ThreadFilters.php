<?php
namespace App\Filters;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class ThreadFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected array $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username): Builder
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    protected function popular(): Builder
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered(): Builder
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->where('replies_count', '=', 0);
    }
}
