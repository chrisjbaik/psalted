<div class="row-fluid">
	<h2 class='song-title'>Edit Song</h2>
	<div class="span5 well">
	  <h4>Chords</h4>
	  <form id="input" method="post" action="/edit/<?php echo $id; ?>">
			<input type='text' name='title' value='<?php echo $title; ?>'>
			<label for="original_key">Original Key:</label>
      <select name="original_key" id="original_key">
        <option value="0">C
        <option value="1">C♯ / D♭
        <option value="2">D
        <option value="3">C♯ / E♭
        <option value="4">E
        <option value="5">F
        <option value="6">F♯ / G♭
        <option value="7">G
        <option value="8">G♯ / A♭
        <option value="9" selected>A
        <option value="10">A♯ / B♭
        <option value="11">B
      </select>
			<label for='chords'>Chords</label>
			<textarea name='chords'><?php echo $chords; ?></textarea>
			<button class="preview-button btn">Preview</button>
			<input type='submit' value='Edit' class='btn btn-primary'>
	  </form>
	</div>
	<div class="span5 well">
	  <h4>Preview</h4>
	  <select name="transposed_key" id="transposed_key">
      <option value="0">C
      <option value="1">C♯ / D♭
      <option value="2">D
      <option value="3">C♯ / E♭
      <option value="4">E
      <option value="5">F
      <option value="6">F♯ / G♭
      <option value="7">G
      <option value="8">G♯ / A♭
      <option value="9" selected>A
      <option value="10">A♯ / B♭
      <option value="11">B
    </select>
	  <section id="output"></section>
	</div>
</div>