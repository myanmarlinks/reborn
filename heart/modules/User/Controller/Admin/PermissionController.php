<?php

namespace User\Controller\Admin;
use Reborn\Connector\Sentry\Sentry;
use User\Model\Group as Group;

class PermissionController extends \AdminController
{
	public function before() 
	{	
		$this->menu->activeParent('user_management');
		$this->template->header = \Translate::get('user::permission.title');
		if(!Sentry::check()) return \Redirect::to('login');
	}

	/**
	* Get groups to edit permissions for each group
	*
	*/
	public function index()
	{
		$group = Group::all();

		$this->template->title(\Translate::get('user::permission.title'))
				->breadcrumb(\Translate::get('user::permission.title'))
				->set('group', $group)
				->setPartial('admin/permission/index');
	}

	/**
	* Edit permissions for each usergroup
	*
	* @param int $groupid
	*/
	public function edit($groupid)
	{
		if ($groupid == 1) return \Redirect::to('admin/user/permission');

		$group = Sentry::getGroupProvider()->findById($groupid);

		if ( !$group ) {
			return \Redirect::to('admin/user/permission');
		}

		// Get permission from the installed modules
		$permission_modules = \User\Model\PermissionModel::getall();

		if (\Input::isPost()) {
			if (\Security::CSRFvalid()) {
				$modules = \Input::get('modules');

				if (!is_null($modules)) {
					foreach ($modules as $k => $v) {
						$modules[$k] = 1;
						foreach ($permission_modules as $m) {
							if(empty($modules[$m->uri])) {
								$modules[$m->uri] = 0;
							}
						}
					}

					$group->permissions = $modules;

					if ($group->save()) {
				       \Flash::success(\Translate::get('user::permission.save'));
						return \Redirect::to('admin/user/permission/');
				    } else {
				    	\Flash::success(\Translate::get('user::permission.error'));
				    	return \Redirect::to('admin/user/permission/');
				    }
				}
			} else {
				\Flash::error(\Translate::get('user::user.csrf'));
			}			
		}

		$groupPermissions = $group->getPermissions();

		$this->template->title(\Translate::get('user::permission.title'))
				->breadcrumb(\Translate::get('user::permission.title'))
				->set('groupPermissions', $groupPermissions)
				->set('permission_modules', $permission_modules)
				->set('group', $group)
				->setPartial('admin/permission/edit');
	}
}