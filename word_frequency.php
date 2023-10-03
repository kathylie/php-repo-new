<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Word Frequency Counter</h1>
    
    <form action="process.php" method="post">
        <label for="text">Paste your text here:</label><br>
        <textarea id="text" name="text" rows="10" cols="50" required></textarea><br><br>
        
        <label for="sort">Sort by frequency:</label>
        <select id="sort" name="sort">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select><br><br>
        
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" value="10" min="1"><br><br>
        
        <input type="submit" value="Calculate Word Frequency">
    </form>
</body>
</html>
<?php
// Function to calculate word frequency while ignoring stop words
function calculateWordFrequency($words) {
    $stopWords = ["the", "and", "in"]; // Common stop words to ignore
    
    $wordFrequency = array_count_values($words);
    
    // Remove stop words from the frequency array
    foreach ($stopWords as $stopWord) {
        unset($wordFrequency[$stopWord]);
    }
    
    return $wordFrequency;
}

// Function to sort word frequency based on user's choice
function sortWordFrequency($wordFrequency, $sortOrder) {
    if ($sortOrder === "asc") {
        asort($wordFrequency);
    } else {
        arsort($wordFrequency);
    }
    return $wordFrequency;
}

// Function to limit the number of words displayed
function limitWordFrequency($wordFrequency, $limit) {
    return array_slice($wordFrequency, 0, $limit);
}

// Initialize variables
$inputText = "";
$selectedSortOrder = "asc"; // Default sorting order
$selectedLimit = 10; // Default number of words to display
$limitedWordFrequency = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $inputText = $_POST['text'];
    $selectedSortOrder = $_POST['sort']; // 'asc' or 'desc'
    $selectedLimit = $_POST['limit']; // Number of words to display

    // Tokenize the input text into words
    $words = str_word_count(strtolower($inputText), 1);

    // Calculate word frequency
    $wordFrequency = calculateWordFrequency($words);

    // Sort word frequency based on user's choice
    $sortedWordFrequency = sortWordFrequency($wordFrequency, $selectedSortOrder);

    // Limit the number of words to display
    $limitedWordFrequency = limitWordFrequency($sortedWordFrequency, $selectedLimit);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Results</title>
    <style>
        /* CSS styles here... */
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label, select, input {
            display: block;
            margin-bottom: 10px;
        }

        select, input[type="number"] {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Word Frequency Results</h1>

    <!-- HTML form for user input -->
    <form method="POST">
        <label for="text">Enter Text:</label>
        <textarea name="text" id="text" rows="4" cols="50"><?= $inputText ?></textarea>
        <br>
        <label for="sort">Sort Order:</label>
        <select name="sort" id="sort">
            <option value="asc" <?= ($selectedSortOrder === 'asc') ? 'selected' : '' ?>>Ascending</option>
            <option value="desc" <?= ($selectedSortOrder === 'desc') ? 'selected' : '' ?>>Descending</option>
        </select>
        <br>
        <label for="limit">Number of Words to Display:</label>
        <input type="number" name="limit" id="limit" value="<?= $selectedLimit ?>" min="1">
        <br>
        <input type="submit" value="Submit">
    </form>

    <!-- Display word frequency results in a table -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <table>
            <tr><th>Word</th><th>Frequency</th></tr>
            <?php foreach ($limitedWordFrequency as $word => $frequency): ?>
                <tr><td><?= $word ?></td><td><?= $frequency ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>
