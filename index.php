<?php

declare(strict_types=1);

// Display header on the first run
echo "===================================\n";
echo "   Knight's Tour Problem           \n";
echo "   Warnsdorff's Algorithm          \n";
echo "===================================\n\n";

/**
 * Possible knight moves
 */
function getKnightMoves(): array
{
    return [
        ['dx' => 2, 'dy' => 1], ['dx' => 1, 'dy' => 2], ['dx' => -1, 'dy' => 2], ['dx' => -2, 'dy' => 1],
        ['dx' => -2, 'dy' => -1], ['dx' => -1, 'dy' => -2], ['dx' => 1, 'dy' => -2], ['dx' => 2, 'dy' => -1]
    ];
}

/**
 * Check if the move is valid
 */
function isValid(int $x, int $y, array $board, int $size): bool
{
    return $x >= 0 && $x < $size && $y >= 0 && $y < $size && $board[$x][$y] === -1;
}

/**
 * Count possible moves from a given position
 */
function countMoves(int $x, int $y, array $board, int $size): int
{
    $moves = getKnightMoves();
    $count = 0;

    foreach ($moves as $move) {
        $nx = $x + $move['dx'];
        $ny = $y + $move['dy'];

        if (isValid($nx, $ny, $board, $size)) {
            $count++;
        }
    }

    return $count;
}

/**
 * Knight's tour algorithm using Warnsdorff's heuristic
 */
function knightTourWarnsdorff(int $size, int $startX, int $startY): void
{
    $board = array_fill(0, $size, array_fill(0, $size, -1)); // Initialize the board with -1
    $board[$startX][$startY] = 0; // First move

    $x = $startX;
    $y = $startY;

    for ($move = 1; $move < $size * $size; $move++) {
        $nextX = -1;
        $nextY = -1;
        $minMoves = 9; // Maximum number of possible moves is 8, so 9 is an impossible value

        // Select the next move based on Warnsdorff's rule
        foreach (getKnightMoves() as $m) {
            $nx = $x + $m['dx'];
            $ny = $y + $m['dy'];

            if (isValid($nx, $ny, $board, $size)) {
                $moves = countMoves($nx, $ny, $board, $size);

                if ($moves < $minMoves) {
                    $minMoves = $moves;
                    $nextX = $nx;
                    $nextY = $ny;
                }
            }
        }

        if ($nextX === -1 || $nextY === -1) {
            echo "Solution not found!\n";
            return;
        }

        // Make the move
        $x = $nextX;
        $y = $nextY;
        $board[$x][$y] = $move;
    }

    // Display the final board
    foreach ($board as $row) {
        foreach ($row as $cell) {
            printf("%2d ", $cell);
        }
        echo "\n";
    }
}

/**
 * User input function
 */
function getUserInput(string $prompt): int
{
    while (true) {
        echo $prompt;
        $input = trim(fgets(STDIN));

        if (is_numeric($input) && (int)$input >= 0) {
            return (int)$input;
        }

        echo "Please enter a valid number!\n";
    }
}

// Main program loop
while (true) {
    $size = getUserInput("Enter the size of the chessboard (minimum 5): ");

    if ($size < 5) {
        echo "The size must be at least 5!\n";
        continue;
    }

    $startX = getUserInput("Enter the starting X-coordinate (0-" . ($size - 1) . "): ");
    $startY = getUserInput("Enter the starting Y-coordinate (0-" . ($size - 1) . "): ");

    if ($startX >= $size || $startY >= $size) {
        echo "Starting coordinates are out of bounds!\n";
        continue;
    }

    knightTourWarnsdorff($size, $startX, $startY);

    echo "Would you like to try again? (y/n): ";
    $answer = trim(fgets(STDIN));

    if (strtolower($answer) !== 'y') {
        break;
    }
}

echo "Program finished.\n";
