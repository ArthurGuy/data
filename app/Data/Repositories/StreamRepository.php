<?php namespace Data\Repositories;

use Aws\DynamoDb\Exception\ValidationException;
use Aws\DynamoDb\Iterator\ItemIterator;

class StreamRepository extends AbstractDynamoRepository {



    public function __construct()
    {
        parent::__construct();
        if (\App::environment() == 'production')
        {
            $this->table = 'streams';
        }
        else
        {
            $this->table = \App::environment().'-streams';
        }
    }

    public function get($id)
    {
        $iterator = new ItemIterator($this->client->getItem(array(
            'ConsistentRead' => true,
            'TableName' => $this->table,
            'Key'       => array(
                'id'   => array('S' => $id)
            )
        )));

        $stream = $iterator->getFirst()->toArray();

        if (!isset($stream['tags']))
        {
            $stream['tags'] = [];
        }
        if (!isset($stream['fields']))
        {
            $stream['fields'] = [];
        }
        else
        {
            $stream['fields'] = json_decode($stream['fields'], true);
        }
        return $stream;
    }


    public function getAll()
    {
        $iterator = new ItemIterator($this->client->getIterator('Scan', array(
            'TableName' => $this->table
        )));
        $results = [];
        foreach ($iterator as $item)
        {
            $itemData = $item->toArray();
            if (!isset($itemData['fields']))
            {
                $itemData['fields'] = [];
            }
            else
            {
                $itemData['fields'] = json_decode($itemData['fields'], true);
            }
            $results[] = $itemData;
        }
        return $results;
    }

    public function create(array $data)
    {

        $streamId = str_random(10);
        $time = time();
        if (is_array($data['fields']))
        {
            $data['fields'] = json_encode($data['fields']);
        }

        try {
            $result = $this->client->putItem(array(
                'TableName' => $this->table,
                'Item' => array(
                    'id'            => array('S' => $streamId),
                    'time_created'  => array('N' => $time),
                    'fields'        => array('S' => $data['fields']),
                    'name'          => array('S' => $data['name']),
                ))
            );
        }
        catch (ValidationException $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function update($streamId, array $data)
    {
        $time = time();
        if (is_array($data['fields']))
        {
            $data['fields'] = json_encode($data['fields']);
        }
        if (!is_array($data['tags']))
        {
            $data['tags'] = explode(',',$data['tags']);
        }
        try {
            $result = $this->client->updateItem(array(
                'TableName' => $this->table,
                'Key' => array(
                    'id' => array(
                        'S' => $streamId,
                    ),
                ),
                'AttributeUpdates' => array(
                    'time_updated'  => array('Value' => array('N' => $time), 'Action' => 'PUT'),
                    'fields'        => array('Value' => array('S' => $data['fields']), 'Action' => 'PUT'),
                    'tags'          => array('Value' => array('SS' => $data['tags']), 'Action' => 'PUT'),
                    'name'          => array('Value' => array('S' => $data['name']), 'Action' => 'PUT'),
                ))
            );
        }
        catch (ValidationException $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->client->deleteItem(array(
            'TableName' => $this->table,
            'Key' => array(
                'id'   => array('S' => $id)
            )
        ));
    }
} 