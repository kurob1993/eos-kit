<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FlowStage extends Pivot
{
  public function scopeFirstSequence($query)
  {
    $t = $this->getTable();

    // SELECT * FROM flow_stage WHERE (sequence) IN
    // ( SELECT MIN(sequence) FROM flow_stage )
    return $query->whereIn('sequence', function ($query) use ($t) {
      $query->from($t)->selectRaw('min(sequence)');
    });
  }

  public function scopeNextSequence($query, $c)
  {
    // select * from stages where `sequence` =
    // (select min(`sequence`) from stages where `sequence` < $c)
    return $query->where('sequence', '=', function ($query) use ($c) {
      $query->from('stages')
            ->selectRaw('min(sequence)')
            ->where('sequence', '<', $c);
    });
  }
}
