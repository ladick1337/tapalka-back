<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{

    use AdminGuard, ApiResponses;

    public function list(Request $request) : array
    {

        $generator = new TableGenerator(
            $this->admin()->notifications()
        );

        $generator->setSortFields(['id', 'created_at', 'read_at']);

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(DatabaseNotification $notify){
                return $notify->only(['id', 'created_at', 'data', 'read_at']);
            })
        );

    }

    public function listUnread(Request $request) : array
    {

        $generator = new TableGenerator(
            $this->admin()->unreadNotifications()
        );

        $generator->setSortFields(['id', 'created_at', 'read_at']);

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(DatabaseNotification $notify){
                return $notify->only(['id', 'created_at', 'data', 'read_at']);
            })
        );

    }

    public function read(Request $request, DatabaseNotification $notification) : void
    {

        if(!$this->admin()->unreadNotifications()->find($notification->id)){
            throw new ApiError('Невозможно прочитать данное уведомление');
        }

        $notification->markAsRead();

    }

    public function readAll(Request $request) : void
    {

        DB::transaction(function(){
            $this->admin()->unreadNotifications()->get()->each(function(DatabaseNotification $notification){
                $notification->markAsRead();
            });
        });

    }

}
