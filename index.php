<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Orca</h1>
    </header>
    <main>
        <h2>Comments</h2>
        <form class="post" action="submit">
            <fieldset>
                <input class="postEmail" placeholder="Email">
                <input class="postName" placeholder="Name">
                <textarea class="postContent" placeholder="Comment" ></textarea>
            </fieldset>
            <div class="postError"></div>
            <button type="submit">Submit</button>
        </form>
        <section class="comments">
        
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>