<h2>Compose your message</h2>
<form>
  <fieldset>
  <legend>Envelope</legend>
  <label to="from">
    From: <input disabled type="text" placeholder="From" value ="<?php echo $data->from ;?>" >
  </label>
  <label to="to">
    To: <input type="text" name="to" placeholder="To" value="<?php echo empty($data->to)?"":$data->to ;?>" list="ulist" >
  </label>
  </fieldset>
  <fieldset class="message">
    <legend>Your message</legend>
    <label for="title">
    Message Title: 
    <input name="title" type="text" placeholder="A title for the message" >
    </label>
    <label for="body">
    Message Body:
    <textarea name="body" rows="10" cols="95" placeholder="Your message here" ></textarea>
    </label>
  </fieldset>
  <button type="submit" name="action" value="discard">Discard</button>
  <button type="submit" name="action" value="save">Save for later</button>
  <button type="submit" name="action" value="send">Send</button>

<datalist id="ulist">
  <?php if ( !empty($data->ulist)): foreach ($data->ulist as $u): ?>
  <option> <?php echo $u ; ?> </option>
  <?php endforeach; endif; ?>
</datalist>

</form>

