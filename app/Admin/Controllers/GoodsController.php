<?php

namespace App\Admin\Controllers;

use App\Good;
use App\Group;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $group='';
    protected $title = 'Товары';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $this->getHeader();

        $grid = new Grid(new Good);

        $grid->model()->where('group_id', session('group_id'));

        $grid->header(function ($query) {
            return "<div style='padding: 10px;'>Группа: <b>".$this->group."</b></div>";
        });

        $grid->column('name', __('Товар'));
        $grid->column('size', __('Объем/кол-во'));
        $grid->column('price', __('Цена'));
        $grid->content('Фото')->display(function ($image) {
            $link = '';
            if ($this->file) {
                $link = '<a href="javascript:removePhoto(\''.$this->id.'\')"  class="photo'.$this->id.'" title="удалить фото">удалить</a>
                <img class="photo'.$this->id.'" src="/uploads/'.$this->file.'" style="width: 150px;padding:5px; ">';
            }
            return $link;
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Good::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('group_id', __('Group id'));
        $show->field('name', __('Name'));
        $show->field('size', __('Size'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Good);

        $form->hidden('group_id')->value(session('group_id'));
        $form->text('name', __('Наименование'));
        $form->text('size', __('Объем/кол-во'));
        $form->decimal('price', __('Цена'))->default(0);
        $form->image('file', 'Фото');


        return $form;
    }

    public function getHeader()
    {
        $group = Group::find(session('group_id'));
//        dd($group->name);
        $this->group = $group->name;
        $this->title .= ' - '.$group->name;
//            dd($this->title);
    }
}
