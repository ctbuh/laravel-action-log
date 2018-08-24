<?php

namespace ctbuh\ActionLog;

use Illuminate\Database\Eloquent\Model;

trait ActionableTrait {
	
	// https://github.com/VentureCraft/revisionable/blob/master/src/Venturecraft/Revisionable/RevisionableTrait.php#L53
	public static function boot(){
		parent::boot();
		
		// not supported in laravel 4
		if(!method_exists(get_called_class(), 'bootTraits')){
			static::bootActionableTrait();
		}
	}
	
	public static function bootActionableTrait(){
		
		// is called when the model is saved for the first time.
		// https://github.com/VentureCraft/revisionable/blob/master/src/Venturecraft/Revisionable/RevisionableTrait.php#L207
		static::created(function($model){
			action_event($model, 'create', $model);
		});
		
		// save() or update() => saving updating updated saved
		//  save() or update() without any changes => saving saved
		// updated only gets called on EXISTING models
		static::updated(function($model){
			
			// TODO: filter()
			// TODO: config.batch_update = true
			foreach($model->getDirty() as $key => $value){
				$before = $model->getOriginal($key);
				
				// we do not care for '0' to 0 changes
				if($before != $value){
					action_event($model, 'update', $key, $value, array('before' => $before));
				}
			}
			
		});
		
		static::deleted(function($model){
			action_event($model, 'delete', $model);
		});
		
		// will not exist without soft delete trait
		if(method_exists(__CLASS__, 'restored') ){
			
			static::restored(function($model){
				action_event($model, 'restore');
			});
		}
		
	}
}
