<form method="POST" id="keywordform" action="generatekeywords.php" class="keywordgen">
  
  <input type="text" name="book_title" id="bookname" placeholder="Type your book name" required> <br>
  
  <select name="num_keywords"> 
    <option value=""> Select number of Keywords </option>
    <option value="5"> 5 Keywords </option>
    <option value="10"> 10 Keywords</option>
    <option value="15"> 15 Keywords </option>
    <option value="20"> 20 Keywords </option>
    <option value="25"> 25 Keywords </option>
  </select>
  
  <select name="bookstore"> 
    <option value="amazon"> Amazon KDP </option>
    <option value="kobo-rakuten"> Kobo Rakuten</option>
    <option value="barnes-noble"> Barnes & Noble </option>
    <option value="apple-books"> Apple Books </option>
    <option value="google-play"> Google Play Books </option>
  </select>
  
  <textarea name="book_description" rows="10" cols="60" placeholder="Paste your book description here"></textarea><br>
  
  <input type="text" name="author_name" placeholder="Author's name"> <br>
  
  <select name="genre">
    <option value=""> Select genre </option>
    <option value="fiction"> Fiction </option>
    <option value="non-fiction"> Non-Fiction </option>
    <option value="mystery"> Mystery </option>
    <option value="romance"> Romance </option>
    <option value="sci-fi"> Science Fiction </option>
    <option value="fantasy"> Fantasy </option>
  </select>
  
  <input type="text" name="target_audience" placeholder="Target audience (e.g., Young Adult, Adult)"> <br>
  
  <button type="submit" name="submit" style="color:white; background-color: black; border-radius: 4px; border:none; outline:none; padding: 10px" class="generatebutton">Generate Keywords</button>
  <input type="reset">

</form>