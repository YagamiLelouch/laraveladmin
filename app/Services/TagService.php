<?php
/**
 * Created by PhpStorm.
 * User: Wen
 * Date: 2018/9/25/025
 * Time: 22:57
 */

namespace App\Services;
use App\Models\Admin\Tag;
use App\Models\Admin\Product;
use App\Models\Admin\Api;

class TagService
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function tagSet($tags, $id, $type)
    {
        
        $allTags = $this->tag->all()->pluck('name');
        $newTags = collect($tags)->diff($allTags)->toArray();
        if ($newTags) {
            foreach ($newTags as $k => $v) {
                $newTagsArray[] = ['name' => $v];
            }
            //批量插入
            $this->tag->insert($newTagsArray);
        }
        //获取所有$tags的id
        $allTagsId = $this->tag->whereIn('name', $tags)->select('id')->get()->toArray();
        foreach ($allTagsId as $k1 => $v1) {
            $allTagsIdArray[$v1['id']] = ['type' => $type];
        }

        if($type == 1) {
            $obj = Product::find($id);
        } elseif ($type == 2) {
            $obj = Api::find($id);
        }


        $obj->tag()->attach($allTagsIdArray);

    }

    public function getTags($tags)
    {
        $tagStr = '';
        foreach ($tags as $v) {
            $tagStr .= $v['name'].',';
        }
        return rtrim($tagStr,",");
    }

}