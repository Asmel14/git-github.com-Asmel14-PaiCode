<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

abstract class BaseModel
{
    protected PDO $db;
    protected string $table = '';
    protected array $primaryKey = ['id'];
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function all(int $limit = 1000, int $offset = 0): array
    {
        $sql = sprintf('SELECT * FROM `%s` LIMIT :limit OFFSET :offset', $this->table);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(array $criteria): ?array
    {
        $rows = $this->where($criteria, 1);
        return $rows[0] ?? null;
    }

    public function where(array $criteria, int $limit = 100): array
    {
        if ($criteria === []) {
            throw new InvalidArgumentException('Criteria cannot be empty.');
        }

        [$whereSql, $params] = $this->buildWhere($criteria);
        $sql = sprintf('SELECT * FROM `%s` WHERE %s LIMIT :limit', $this->table, $whereSql);
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create(array $data): ?int
    {
        $data = $this->filterFillable($data);

        if ($data === []) {
            throw new InvalidArgumentException('Data cannot be empty.');
        }

        $columns = array_keys($data);
        $columnSql = '`' . implode('`, `', $columns) . '`';
        $placeholders = ':' . implode(', :', $columns);
        $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $this->table, $columnSql, $placeholders);

        $stmt = $this->db->prepare($sql);
        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }

        $stmt->execute();

        $lastId = $this->db->lastInsertId();
        return $lastId === '0' ? null : (int) $lastId;
    }

    public function update(array $criteria, array $data): int
    {
        $data = $this->filterFillable($data);

        if ($criteria === []) {
            throw new InvalidArgumentException('Criteria cannot be empty.');
        }

        if ($data === []) {
            throw new InvalidArgumentException('Data cannot be empty.');
        }

        $setParts = [];
        $setParams = [];
        foreach ($data as $column => $value) {
            $key = ':s_' . $column;
            $setParts[] = sprintf('`%s` = %s', $column, $key);
            $setParams[$key] = $value;
        }

        [$whereSql, $whereParams] = $this->buildWhere($criteria);
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $this->table, implode(', ', $setParts), $whereSql);
        $stmt = $this->db->prepare($sql);

        foreach (array_merge($setParams, $whereParams) as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(array $criteria): int
    {
        if ($criteria === []) {
            throw new InvalidArgumentException('Criteria cannot be empty.');
        }

        [$whereSql, $params] = $this->buildWhere($criteria);
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $this->table, $whereSql);
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    protected function filterFillable(array $data): array
    {
        if ($this->fillable === []) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function buildWhere(array $criteria): array
    {
        $parts = [];
        $params = [];
        $index = 0;

        foreach ($criteria as $column => $value) {
            $paramKey = ':w_' . $index;
            $parts[] = sprintf('`%s` = %s', $column, $paramKey);
            $params[$paramKey] = $value;
            $index++;
        }

        return [implode(' AND ', $parts), $params];
    }
}
