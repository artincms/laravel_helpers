<?php
namespace ArtinCMS\LHS;
use Illuminate\Support\Facades\Facade;

class LHSFacade extends Facade
{
	protected static function getFacadeAccessor() {
		return 'LHS';
	}
}