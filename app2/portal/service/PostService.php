<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\service;

use app\portal\model\PortalPostModel;
use app\portal\model\PortalXmModel;
class PostService
{

    public function adminArticleList($filter,$page)
    {
        return $this->adminPostList($filter,$page);
    }

    public function adminXmAllList($filter)
    {
        return $this->adminXmList($filter);
    }

    public function adminPageList($filter,$page=true)
    {
        return $this->adminPostList($filter, $page);
    }

    public function adminPostList($filter, $page,$isPage = false)
    {

        $category = empty($filter['category']) ? 0 : intval($filter['category']);
        if (!empty($category)) {
            $where['a.parent_id'] = ['eq', $category];
        }

//        $categorys = empty($filter['categorys']) ? '' : $filter['categorys'];
//        if (!empty($categorys)) {
//            $category_arr = db('portal_category')->where("name = '$categorys'")->find();
//            $where['a.parent_id'] = ['eq', $category_arr['id']];
//        }

        $startTime = empty($filter['start_time']) ? 0 : strtotime($filter['start_time']);
        $endTime   = empty($filter['end_time']) ? 0 : strtotime($filter['end_time']);
        if (!empty($startTime) && !empty($endTime)) {
            $where['a.published_time'] = [['>= time', $startTime], ['<= time', $endTime]];
        } else {
            if (!empty($startTime)) {
                $where['a.published_time'] = ['>= time', $startTime];
            }
            if (!empty($endTime)) {
                $where['a.published_time'] = ['<= time', $endTime];
            }
        }

        $keyword = empty($filter['keyword']) ? '' : $filter['keyword'];
        if (!empty($keyword)) {
            $where['a.post_title'] = ['like', "%$keyword%"];
        }
        $status = isset($filter['status']) ? $filter['status'] : 0 ;
        if($status>0){
            $where['a.post_status'] = 1;
            if($status > 1 ){
                $where['a.post_status'] = 0;
            }
        }
        $artid = empty($filter['artid']) ? '' : $filter['artid'];
        if(!empty($artid)){
            $where['a.id'] = $artid;
        }

        if ($isPage) {
            $where['a.post_type'] = 2;
        } else {
            $where['a.post_type'] = 1;
        }
        $where['a.status'] = 1;
        $where1 = '';
        if (!empty($filter['flags'])) {
            //$where1= ['in',$filter['flags']];
            $where1 = 'FIND_IN_SET("'.$filter['flags'].'",`a`.`flag`)';
        }
        if (!empty(trim($filter['writer']))) {
//            $where['b.user_login'] = [ 'like',"%".$filter['writer']."%"];
            $where['a.author'] = [ 'like',"%".$filter['writer']."%"];
        }
        if(!empty(trim($filter['categorys']))){
            $where['c.name'] = ['like',"%".$filter['categorys']."%"];
        }
        $portalPostModel = new PortalPostModel();
        $articles        = $portalPostModel
            ->alias('a')
            ->where($where)
            ->where($where1)
//            ->join('user b ','a.user_id = b.id ','LEFT')
            ->join('portal_category c ','a.parent_id = c.id ','LEFT')
            ->field('a.*,c.name')
            ->order('a.update_time', 'DESC')
            ->paginate(50,false,['page'=>$page]);

//        echo $portalPostModel->getLastSql();die;
        return $articles;

    }

    public function publishedArticle($postId, $categoryId = 0)
    {
        $portalPostModel = new PortalPostModel();

        if (empty($categoryId)) {

            $where = [
                'post.post_type'      => 1,
                'post.published_time' => [['< time', time()], ['> time', 0]],
                'post.post_status'    => 1,
                'post.delete_time'    => 0,
                'post.id'             => $postId
            ];

            $article = $portalPostModel->alias('post')->field('post.*')
                ->where($where)
                ->find();
        } else {
            $where = [
                'post.post_type'       => 1,
                'post.published_time'  => [['< time', time()], ['> time', 0]],
                'post.post_status'     => 1,
                'post.delete_time'     => 0,
                'relation.category_id' => $categoryId,
                'relation.post_id'     => $postId
            ];

            $join    = [
                ['__PORTAL_CATEGORY_POST__ relation', 'post.id = relation.post_id']
            ];
            $article = $portalPostModel->alias('post')->field('post.*')
                ->join($join)
                ->where($where)
                ->find();
        }


        return $article;
    }

    //上一篇文章
    public function publishedPrevArticle($postId, $categoryId = 0)
    {
        $portalPostModel = new PortalPostModel();

        if (empty($categoryId)) {

            $where = [
                'post.post_type'      => 1,
                'post.published_time' => [['< time', time()], ['> time', 0]],
                'post.post_status'    => 1,
                'post.delete_time'    => 0,
                'post.id '            => ['<', $postId]
            ];

            $article = $portalPostModel->alias('post')->field('post.*')
                ->where($where)
                ->order('id', 'DESC')
                ->find();

        } else {
            $where = [
                'post.post_type'       => 1,
                'post.published_time'  => [['< time', time()], ['> time', 0]],
                'post.post_status'     => 1,
                'post.delete_time'     => 0,
                'relation.category_id' => $categoryId,
                'relation.post_id'     => ['<', $postId]
            ];

            $join    = [
                ['__PORTAL_CATEGORY_POST__ relation', 'post.id = relation.post_id']
            ];
            $article = $portalPostModel->alias('post')->field('post.*')
                ->join($join)
                ->where($where)
                ->order('id', 'DESC')
                ->find();
        }


        return $article;
    }

    //下一篇文章
    public function publishedNextArticle($postId, $categoryId = 0)
    {
        $portalPostModel = new PortalPostModel();

        if (empty($categoryId)) {

            $where = [
                'post.post_type'      => 1,
                'post.published_time' => [['< time', time()], ['> time', 0]],
                'post.post_status'    => 1,
                'post.delete_time'    => 0,
                'post.id'             => ['>', $postId]
            ];

            $article = $portalPostModel->alias('post')->field('post.*')
                ->where($where)
                ->order('id', 'ASC')
                ->find();
        } else {
            $where = [
                'post.post_type'       => 1,
                'post.published_time'  => [['< time', time()], ['> time', 0]],
                'post.post_status'     => 1,
                'post.delete_time'     => 0,
                'relation.category_id' => $categoryId,
                'relation.post_id'     => ['>', $postId]
            ];

            $join    = [
                ['__PORTAL_CATEGORY_POST__ relation', 'post.id = relation.post_id']
            ];
            $article = $portalPostModel->alias('post')->field('post.*')
                ->join($join)
                ->where($where)
                ->order('id', 'ASC')
                ->find();
        }


        return $article;
    }

    public function publishedPage($pageId)
    {

        $where = [
            'post_type'      => 2,
            'published_time' => [['< time', time()], ['> time', 0]],
            'post_status'    => 1,
            'delete_time'    => 0,
            'id'             => $pageId
        ];

        $portalPostModel = new PortalPostModel();
        $page            = $portalPostModel
            ->where($where)
            ->find();

        return $page;
    }

    public function adminXmList($filter, $isPage = false)
    {

        $where = [
            'a.pubdate' => ['>=', 0],
            'a.delete_time' => 0
        ];

        $join = [
            ['__USER__ u', 'a.dutyadmin = u.id']
        ];

        $field = 'a.*,u.user_login,u.user_nickname,u.user_email';

        $category = empty($filter['category']) ? 0 : intval($filter['category']);
        if (!empty($category)) {
            $where['a.type_id'] = ['eq', $category];
            array_push($join, [
                '__PORTAL_CATEGORY__ b', 'a.type_id = b.id'
            ]);
            $field = 'a.*,b.name,b.id,u.user_login,u.user_nickname,u.user_email';
        }

        $startTime = empty($filter['start_time']) ? 0 : strtotime($filter['start_time']);
        $endTime   = empty($filter['end_time']) ? 0 : strtotime($filter['end_time']);
        if (!empty($startTime) && !empty($endTime)) {
            $where['a.published_time'] = [['>= time', $startTime], ['<= time', $endTime]];
        } else {
            if (!empty($startTime)) {
                $where['a.published_time'] = ['>= time', $startTime];
            }
            if (!empty($endTime)) {
                $where['a.published_time'] = ['<= time', $endTime];
            }
        }

        $keyword = empty($filter['keyword']) ? '' : $filter['keyword'];
        if (!empty($keyword)) {
            $where['a.title'] = ['like', "%$keyword%"];
        }

        if ($isPage) {
            $where['a.post_type'] = 2;
        } else {
            $where['a.post_type'] = 1;
        }

        $portalPostModel = new PortalXmModel();
        $articles        = $portalXmModel->alias('a')->field($field)
            ->join($join)
            ->where($where)
            ->order('update_time', 'DESC')
            ->paginate(10);

        return $articles;

    }

}