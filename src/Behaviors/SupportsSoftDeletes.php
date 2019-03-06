<?php
namespace Dev\Mugglequent\Behaviors;

use Illuminate\Database\Eloquent\SoftDeletes;

trait SupportsSoftDeletes
{
    use SoftDeletes;

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function runSoftDelete()
    {
        $query = $this->newModelQuery()->where($this->getKeyName(), $this->getKey());

        $time = $this->freshTimestamp();

        $this->attributes[$this->getDeletedAtColumn()] = $time;
        $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];

        if ($this->timestamps && !is_null($this->getUpdatedAtColumn())) {
            $this->setUpdatedAt($time);
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
    }
}
