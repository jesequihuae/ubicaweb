<?php 
	class Horarios extends Illuminate\Database\Eloquent\Model { 
		protected $table = 'tblhorarios';
		public $timestamps = false;
		protected $primaryKey = 'idHorario';
	}
?>