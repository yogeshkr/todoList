<?php


namespace App\ServiceManager;


use App\Models\Media;
use App\Models\Reminder;
use Illuminate\Http\Request;

class TodoService
{

    public function addMedia(Request $request, $todoId)
    {
        $filePath = storage_path().'/app/';
        $media = Media::where(['todo_id'=> $todoId])->get();
        foreach($media as $img){
            $files = $filePath.$img->media_file;
            if (file_exists($files)) {
                \Illuminate\Support\Facades\File::delete($files);
            }
            $img->delete();
        }

        if ($request->file('media_file')) {
            $file =  $request->file('media_file')->store('images');
            $media = new Media();
            $media->media_file = $file;
            $media->todo_id = $todoId;
            $media->save();
        }
    }

    public function addReminders(Request $request, $todoId)
    {
        if($request->has('reminder_offset') && $request->has('offset_unit'))
        {
            $reminderOffset = '';
            switch ($request->get('reminder_offset'))
            {
                case 'minute':
                    $reminderOffset = 'minute';
                    if($request->get('offset_unit') > 1)
                    {
                        $reminderOffset = 'minutes';
                    }
                    break;

                case 'hour':
                    $reminderOffset = 'hour';
                    if($request->get('offset_unit') > 1)
                    {
                        $reminderOffset = 'hours';
                    }
                    break;

                case 'day':
                    $reminderOffset = 'day';
                    if($request->get('offset_unit') > 1)
                    {
                        $reminderOffset = 'days';
                    }
                    break;
            }

            $reminderOffset = $request->get('offset_unit').' '.$reminderOffset;
            if(empty($requestData['due_date']) || is_null($requestData['due_date'])){
                $sentOn = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + '.$reminderOffset));
            }else{
                $sentOn = date('Y-m-d H:i:s', strtotime($requestData['due_date'].' - '.$reminderOffset));
            }

            $reminder = new Reminder();
            $reminder->todo_id = $todoId;
            $reminder->reminder_offset = $request->get('reminder_offset');
            $reminder->offset_unit = $request->get('offset_unit');
            $reminder->sent_status = 0;
            $reminder->sent_on = $sentOn;
            $reminder->save();
        }
    }
}
