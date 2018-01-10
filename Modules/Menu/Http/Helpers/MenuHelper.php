<?php
namespace Modules\Menu\Http\Helpers;
use App\Option;
use DB;

class MenuHelper 
{
	/**
     * Get list menu for editing menu
     **/
	public static function getListMenu($menu){
        $menu_structure = '';
        if (count($menu) > 0) {
            $menu = json_decode($menu->value);
            foreach ($menu as $key) {
                $menu_structure .= '<li class="dd-item" data-id="'.$key->id.'" data-link="'.$key->link.'" data-label="'.$key->label.'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'.$key->id.'"><div class="menu-title"><span>'.$key->label.'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'.$key->id.'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'.$key->id.'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'.$key->label.'"><label>URL</label><input class="form-control" type="url" name="url" value="'.$key->link.'"></div><a href="#" class="remove_item">Remove</a></div></div></li>';
            }
        }
        return $menu_structure;
    }
}
?>