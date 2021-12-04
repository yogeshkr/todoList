<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $priority
 * @property string $location
 * @property \DateTime $start_time
 * @property bool $completed
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Reminder extends Model
{
    protected $table = 'reminder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'todo_id',
        'reminder_offset',
        'offset_unit',
        'sent_status',
        'sent_on'
    ];

    /**
     * Get todo of Reminder
     *
     * @property $todo
     */
    public function todo()
    {
        return $this->belongsTo(Todo::class, 'todo_id');
    }
}
