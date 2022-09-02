<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/function.php";
require_once('secure.php');

use Pekay\names;

$names = new names;
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

$jsonData = $names->getSortedData();
$jsonKeys = $names->getDataKeys($jsonData, 'Name');

$rowSize =   sizeof($jsonData) % 2 == 0 ? sizeof($jsonData) / 2 : (sizeof($jsonData) + 1) / 2;


if (isset($_POST["submit"])) {
    $response = $names->makeUser();
}
if (isset($_POST["submit2"])) {
    $response = $names->addDataOfWinner($rowSize);
    echo '<script type="text/JavaScript">window.history.replaceState( null, null, window.location.href );</script>';
    echo '<script type="text/JavaScript"> setTimeout(function(){location.reload();}, 5000); </script>';
}


?>
<!DOCTYPE html>
<html lang="en">
<!--
      ██████╗░██╗░░██╗
      ██╔══██╗██║░██╔╝
      ██████╔╝█████═╝
      ██╔═══╝░██╔═██╗
      ██║░░░░░██║░╚██╗
      ╚═╝░░░░░╚═╝░░╚═╝
      -:>-------------
      
      -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Admin

    </title>
    <script src="https://kit.fontawesome.com/c91cfe85aa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" type="module"></script>
    <script src="./assets/js/script.js" type="module"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body class="bg-dark">


    <section class="mt-5 mb-3">
        <div>
            <section>
                <div style="max-width: 500px; border-radius: 20px" class="rounded-lg card mx-auto p-3 mx-3">
                    <?php
                    if (!empty($response["status"])) {
                        if ($response["status"] == "error") {
                    ?>
                    <div class="alert alert-danger"><?php echo $response["message"] ?></div>
                    <?php
                        } elseif ($response["status"] == "success") {
                        ?>
                    <div class="alert alert-success"><?php echo $response["message"] ?></div>
                    <?php
                        }
                    }
                    ?>
                    <div>
                        <a href="/" class="customlink2">
                            <h3 class=" text-center">Add New Player</h3>
                        </a>
                    </div>
                    <form action="" method="post">
                        <div class="form-group mb-2">
                            <label for="name">Player Name</label>
                            <input id="name" name="name" required type="text" class="custominput form-control"
                                placeholder="Max" />
                            <small id="emailHelp" class="form-text text-muted">The name of the player</small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="score">Quiz Score</label>
                            <input id="score" name="score" value="" required type="number" min="1" max="12"
                                class="custominput form-control" placeholder="1" />
                            <small id="emailHelp" class="form-text text-muted">The level from the quiz they took over
                                chess.</small>
                        </div>
                        <button type="submit" name="submit" value="Submit" class="btn btn-primary">Submit</button>
                        <br>
                        <small id="emailHelp" class="form-text text-muted">Reload page to see updates.</small>

                    </form>
                </div>
            </section>
            <section>
                <div>
                    <div style="max-width: 1400px; min-height: 100px; border-radius: 20px"
                        class="rounded-lg card  mt-4 mx-auto p-3 mt-2 mx-3">
                        <form action="" method="post">

                            <div class="name-wrap mx-3">
                                <!-- START COL -->
                                <?php
                                if (!empty($response["status2"])) {
                                    if ($response["status2"] == "error") {
                                ?>
                                <div class="alert alert-danger"><?php echo $response["message"] ?></div>
                                <?php
                                    } elseif ($response["status2"] == "success") {
                                    ?>
                                <div class="alert alert-success"><?php echo $response["message"] ?></div>
                                <?php
                                    }
                                }
                                ?>
                                <?php

                                for ($x = 0; $x <= sizeof($jsonData); $x += 2) {
                                    if (isset($jsonKeys[$x])) {
                                        if (isset($jsonData[$jsonKeys[$x]])) {
                                            $user1Data = $jsonData[$jsonKeys[$x]] ?? null;
                                            if (isset($jsonKeys[$x + 1])) {
                                                if (isset($jsonData[$jsonKeys[$x + 1]])) {
                                                    $user2Data = $jsonData[$jsonKeys[$x + 1]] ?? null;
                                                } else {
                                                    $user2Data = null;
                                                }
                                            } else {
                                                $user2Data = null;
                                            }
                                            $nameval = (($rowSize - ($rowSize - $x)) / 2) + 1; ?>
                                <div class="row" id="user">
                                    <div class="col card mx-1 p-3 m-2 user_1">
                                        <div class="row">
                                            <div class="col-6">

                                                <?php
                                                            if (isset($_POST['r' . $x])) {
                                                                $names->removeJson($jsonKeys[$x]);
                                                                echo '<script type="text/JavaScript">window.history.replaceState( null, null, window.location.href );</script>';
                                                                echo '<script type="text/JavaScript"> location.reload(); </script>';
                                                            }
                                                            ?>

                                                <button style="float:left;" class="btn btn-danger"
                                                    name="<?= 'r' . $x ?>">
                                                    Delete
                                                </button>


                                            </div>




                                            <div class="col-6">
                                                <span>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Name:
                                                    </span>
                                                    <span id="name2">
                                                        <?= $user1Data['Name'] ?? 'To Be Determined' ?>
                                                    </span>
                                                    <br>
                                                    <span class=" text-xs font-weight-bold mb-0">
                                                        Place:
                                                    </span>
                                                    <span>
                                                        <?= $names->calcPlace($user1Data['Name']); ?>
                                                    </span>
                                                    <br>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Number Of Wins:
                                                    </span>
                                                    <span>
                                                        <?= $names->getScore($user1Data['Name']) ?>
                                                    </span>
                                                    <br>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Knowledge Level:
                                                    </span>
                                                    <span>
                                                        <?= $names->getKnowledgeLevel($user1Data['Name']) ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-3 mt-4 vstext">
                                        <?php
                                                    if (isset($jsonKeys[$x + 1])) {
                                                        if (isset($jsonData[$jsonKeys[$x + 1]])) {
                                                    ?>
                                        <select name="winame<?= $nameval ?>" class="form-select "
                                            aria-label="Default select example">
                                            <option style="display:none" selected>Select Winner</option>
                                            <option value="<?= $user1Data['Name'] ?>">
                                                <?= $user1Data['Name'] ?>
                                            </option>
                                            <option value="<?= $user2Data['Name'] ?>">
                                                <?= $user2Data['Name'] ?>
                                            </option>
                                        </select>

                                        <?php
                                                        } else {
                                                            echo 'N/A';
                                                        }
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                    </div>
                                    <div class="col user_2 card mx-1 p-3 m-2 user_1">


                                        <?php
                                                    if ($user2Data) {
                                                    ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <span>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Name:
                                                    </span>
                                                    <span id="name2">
                                                        <?= $user2Data['Name'] ?? 'To Be Determined' ?>
                                                    </span>
                                                    <br>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Place:
                                                    </span>
                                                    <span>
                                                        <?= $names->calcPlace($user2Data['Name']); ?>
                                                    </span>
                                                    <br>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Number Of Wins:
                                                    </span>
                                                    <span>
                                                        <?= $names->getScore($user2Data['Name']) ?>
                                                    </span>
                                                    <br>
                                                    <span class="text-xs font-weight-bold mb-0">
                                                        Knowledge Level:
                                                    </span>
                                                    <span>
                                                        <?= $names->getKnowledgeLevel($user2Data['Name']) ?>
                                                    </span>

                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <?php
                                                                if (isset($_POST['r' . $x + 1])) {
                                                                    $names->removeJson($jsonKeys[$x + 1]);
                                                                    echo '<script type="text/JavaScript">window.history.replaceState( null, null, window.location.href );</script>';
                                                                    echo '<script type="text/JavaScript"> location.reload(); </script>';
                                                                }
                                                                ?>

                                                <button style="float:left;" class="btn btn-danger"
                                                    name="<?= 'r' . $x + 1 ?>">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                        <?php
                                                    } else {

                                                    ?>
                                        <span>
                                            <span id="name2" class="text-xs font-weight-bold mb-0">
                                                <?= $user2Data['Name'] ?? 'To Be Determined' ?>
                                            </span>
                                        </span>

                                        <?php
                                                    }

                                                    ?>

                                    </div>
                                </div>
                                <?php
                                        }
                                    }
                                }
                                ?>

                                <!-- End Ip List -->
                            </div>
                            <div style="width: 100%" class="vstext">
                                <button type="submit" name="submit2" value="Submit2"
                                    class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </section>


</html>