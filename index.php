

<?php include "./template/header.php" ?>

<?php include "./includes/consent.php" ?>




  <main>
    <h2>Latest Posts</h2>
    <table class="blog-list">
      <thead>
        <tr><th>Title</th><th>Description</th><th>Date</th><th>PDF</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><a href="post.php?id=post-1">My First Blog</a></td>
          <td>A simple introduction post.</td>
          <td>Aug 20, 2025</td>
          <td><a href="post1.pdf">Download</a></td>
        </tr>
        <tr>
          <td><a href="post.php?id=post-2">Why Simplicity Wins</a></td>
          <td>Why minimal websites last longer.</td>
          <td>Aug 22, 2025</td>
          <td><a href="post2.pdf">Download</a></td>
        </tr>
      </tbody>
    </table>
  </main>

<?php include "./template/footer.php" ?>
