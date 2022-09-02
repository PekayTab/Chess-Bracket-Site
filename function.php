<?php

namespace Pekay;

class RowsSortHelperTool
{

    /**
     * Sorts the given array, based on the given sorts.
     *
     * The sorts argument is an array of field => direction,
     *
     * with:
     *
     * - field: string, the name of the property to sort the rows with
     * - direction: string (asc|desc), the direction of the sort
     *
     *
     * @param array $rows
     * @param array $sorts
     * @return array
     */
    public static function sort(array &$rows, array $sorts)
    {

        $args = [];

        foreach ($sorts as $field => $direction) {
            $col = array_column($rows, $field);
            $args[] = $col;

            if ('asc' === $direction) {
                $args[] = SORT_ASC;
            } else {
                $args[] = SORT_DESC;
            }
        }
        $args[] = &$rows;
        call_user_func_array("array_multisort", $args);
    }
}
class names


{
    public function readJson($file)
    {
        $currentData = json_decode(file_get_contents($file), true);
        return $currentData;
    }
    public function makeUser()
    {
        $name = $_POST["name"];
        $score = $_POST["score"];
        $insert = [];
        if (empty($name) || empty($score)) {
            $response = [
                "status" => "error",
                "message" => "Name and/or score cannot be empty!"
            ];
            return $response;
        } else {
            if ($score > 12 || $score < 1) {
                $response = [
                    "status" => "error",
                    "message" => "Score cannot be higher than 12 or lower than 1!"
                ];
                return $response;
            } else {
                if (isset($insert[$name])) {
                    $response = [
                        "status" => "error",
                        "message" => "There is already a player with the name: " . $name . "."
                    ];
                    return $response;
                } else {
                    $insert = ['Name' => $name, 'QuizScore' => intval($score), 'Matches' => [], 'TotalScore' => 0];
                    $filename = './players/' . $name . '.json';
                    if (!$name == null) {

                        file_put_contents($filename, json_encode($insert, JSON_PRETTY_PRINT));
                    }
                    $response = [
                        "status" => "success",
                        "message" => 'Succsesfly added player "' . $name . '" to the game.'
                    ];
                    return $response;
                    unset($name, $score, $inp, $tempArray, $data, $jsonData);
                }
            }
        }
    }
    public function getDataKeys($array, $keyname)
    {
        $marks = array();
        foreach ($array as $key => $row) {

            $marks[$array[$key][$keyname]] = $array[$key];

            // Sorts user by there quizscore
        }
        $outKeys = array_keys($marks);
        return $outKeys;
    }
    public function getSortedData()
    {
        $jsonData = [];
        $files = scandir('./players/');
        $files = array_slice($files, 2);
        foreach ($files as $file) {
            $currentData = json_decode(file_get_contents('./players/' . $file), true);

            $Name =    $currentData['Name'] ?? '';
            $QuizScore =    $currentData['QuizScore'] ?? '';
            $Matches =    $currentData['Matches'] ?? '';
            $TotalScore =    $currentData['TotalScore'] ?? '';
            $jsonData[$Name] =   [
                'Name' =>  $Name,
                'QuizScore' =>  $QuizScore,
                'Matches' => $Matches,
                'TotalScore' =>  $TotalScore,
            ];
        }
        //Forms data using all user files
        RowsSortHelperTool::sort($jsonData, [
            'TotalScore' => 'desc',
            'QuizScore' => 'desc',
        ]);

        return $jsonData;
    }
    public function getScore($user)
    {
        $filename = './players/' . $user . '.json';
        $data = $this->readJson($filename);
        $score = $data['TotalScore'];
        return  intval($score);
    }

    public function calcPlace($user)
    {

        $jsonData = $this->getSortedData();
        $jsonKeys = $this->getDataKeys($jsonData, 'Name');

        $returnVal = array_search($user, $jsonKeys);
        $returnVal = $returnVal + 1;
        return $returnVal;
    }
    public function getMatches($user)
    {
        $filename = './players/' . $user . '.json';
        $data = $this->readJson($filename);
        $matches = $data['Matches'];
        return $matches;
    }
    public function getKnowledgeLevel($user)
    {
        $filename = './players/' . $user . '.json';
        $data = $this->readJson($filename);
        $score = $data['QuizScore'];
        return  intval($score);
    }


    public function addDataOfWinner($rowSize)
    {
        $winners = [];
        for ($x = 0; $x <= $rowSize - 1; $x++) {
            $data = $_POST["winame" . $x + 1];
            // Validate
            if ($data !== "Select Winner") {
                $winner =  $data;
                $response = [
                    "status2" => "success",
                    "message" => "Successfully updated winners and the brackets!"
                ];
                array_push($winners, $winner);
            } else {
                $winner = false;
                $response = [
                    "status2" => "error",
                    "message" => "Winner not selected in row " . ($x + 1) . "!"
                ];
                return $response;
            }
        }
        foreach ($winners as $validwinner) {
            $jsonData = $this->getSortedData();
            $jsonKeys = $this->getDataKeys($jsonData, 'Name');

            // Update winner
            $insert = [];
            $filename = './players/' . $validwinner . '.json';
            $currentData = json_decode(file_get_contents($filename), true);
            $Name = $currentData['Name'] ?? '';
            $QuizScore = $currentData['QuizScore'] ?? '';
            $Matches =  $currentData['Matches'] ?? '';
            $TotalScore = $currentData['TotalScore'] ?? '';
            $insert = ['Name' => $Name, 'QuizScore' => intval($QuizScore), 'Matches' => $Matches, 'TotalScore' =>  intval($TotalScore) + 1];
            file_put_contents($filename, json_encode($insert, JSON_PRETTY_PRINT));
            unset($currentData, $insert, $Name, $filename, $QuizScore, $Matches, $TotalScore);
        }
        return $response;
    }

    public function removeJson($name)
    {
        print_r($name);
        $file = './players/' . $name . '.json';
        if (unlink($file)) {
            return 'File ' . $file . ' deleted';
        } else {
            return 'Was unable to delete the file' . $file . '.';
        }
    }
}