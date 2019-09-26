<?php

namespace Dirim\BeginningPackage\ModelsRepository;

trait Repository
{
    protected $lang;
    
    public function __construct(Array $id = [])
    {
        parent::__construct($id);
        $this->lang = \Illuminate\Support\Facades\App::getLocale();
    }

    public function scopeDataList($query, Array $info)
    {
        /*Select field Query*/
        $selectCol = array_diff(
            $info['selectCol'],
            $info['addLangFields']
        );
        $select = 't0.'.implode(', t0.', $selectCol);

        /*Like Query*/
        array_walk($info['searchCol'], function (&$item, $key) use ($info) {
            $item = $item === $info['fieldIDName']
                        ? 't0.'.$item
                        : $item;
        });
            
        $search = "'%{$info['search']}%'";
        $like = implode(
            " LIKE {$search} OR ",
            $info['searchCol']
        );
        $like .= " LIKE {$search}";
        $like = '('.$like.')';
        
        /*From Query*/
        $from = "{$info['table']} as t0";

        if (count($info['addLangFields']) > 0) {
            /*Select Language field Query*/
            $select .= ', t1.';
            $select .= implode(', t1.', $info['addLangFields']);
            
            /*Language Inner Joın Query*/
            $langTbl = "{$info['table']}_lang";

            $join = [
                "{$langTbl} as t1",
                "t0.{$info['fieldIDName']}", '=',
                "t1.{$info['fieldIDName']}"
            ];

            /*Language Choice*/
            $whereRaw = [
                "t1.{$info['fieldDependsOnLang']} = :lang",
                ['lang' => $this->lang]
            ];
        }

        $query->from($from)
        ->selectRaw($select);

        if (isset($join) && isset($whereRaw)) {
            $query->join(...$join)
            ->whereRaw(...$whereRaw);
        }

        $query->orderBy($info['colOrder'], $info['order']);

        if ($info['search']) {
            $query->whereRaw($like);
        }

        return $query;
    }

    public function scopeUpdateMany($query, Array $datas)
    {
        extract($datas);
        $childIDName = $childIDName ?? 'id';

        $childModels = collect([]);
        
        $destroyIDs = $this->$childName
                            ->pluck($childIDName)
                            ->toArray();

        foreach ($childDatas as $childData) {
            $childID = $childData[$childIDName] ?? -1;
            
            $index = array_search(
                $childID,
                $destroyIDs
            );
            
            if ($index !== false) {
                unset($destroyIDs[$index]);
                $childModels->push(
                    $this->$childName[$index]->fill($childData)
                );
            } else {
                $childModels->push(
                    (clone $childInstance)->fill($childData)
                );
            }
        }
        
        if (count($destroyIDs) > 0) {
            $childInstance::destroy($destroyIDs);
        }

        $bksl = $this->$childName()->saveMany($childModels);
        return $this->setRelations([$childName => $bksl]);
    }
}
