<?php
    class ElementSearchOptions extends Element {
        public function Render() {
            global $page;
            $page->AttachStylesheet( 'css/search.css' );
            if ( UserBrowser() == "MSIE" ) {
                $page->AttachStylesheet( 'css/search-ie.css' );
            }
?><div id="search">
        <h2>ÎÎ½Î±Î¶Î®ÏÎ·ÏÎ· Î±ÏÏÎ¼ÏÎ½</h2>
        <div class="ybubble">
            <i class="tl"></i>
            <i class="tr"></i>
            <div class="body">
            <form action="">
                <div class="search">
                    <h3>Î¦ÏÎ»Î¿:</h3>
                    <input type="radio" name="gender" value="male" id="gender_male" /><label for="gender_male">ÎÎ³ÏÏÎ¹Î±</label>
                    <input type="radio" name="gender" value="female" id="gender_female" /><label for="gender_female">ÎÎ¿ÏÎ­Î»ÎµÏ</label>
                    <input type="radio" name="gender" value="male" id="gender_both" checked="checked" /><label for="gender_both">ÎÎ±Î¹ ÏÎ± Î´ÏÎ¿</label>
                </div>
                
                <div class="search">
                    <h3>ÎÎ»Î¹ÎºÎ¯Î±:</h3>
                    Î±ÏÏ: 
                    <select name="minage">
                        <option value="any">Î±Î´Î¹Î¬ÏÎ¿ÏÎ¿</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                        <option value="60">60</option>
                        <option value="61">61</option>
                        <option value="62">62</option>
                        <option value="63">63</option>
                        <option value="64">64</option>
                        <option value="65">65</option>
                        <option value="66">66</option>
                        <option value="67">67</option>
                        <option value="68">68</option>
                        <option value="69">69</option>
                        <option value="70">70</option>
                        <option value="71">71</option>
                        <option value="72">72</option>
                        <option value="73">73</option>
                        <option value="74">74</option>
                        <option value="75">75</option>
                        <option value="76">76</option>
                        <option value="77">77</option>
                        <option value="78">78</option>
                        <option value="79">79</option>
                        <option value="80">80</option>
                    </select> 
                    
                    Î­ÏÏ: 
                    <select name="maxage">
                        <option value="any">Î±Î´Î¹Î¬ÏÎ¿ÏÎ¿</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                        <option value="60">60</option>
                        <option value="61">61</option>
                        <option value="62">62</option>
                        <option value="63">63</option>
                        <option value="64">64</option>
                        <option value="65">65</option>
                        <option value="66">66</option>
                        <option value="67">67</option>
                        <option value="68">68</option>
                        <option value="69">69</option>
                        <option value="70">70</option>
                        <option value="71">71</option>
                        <option value="72">72</option>
                        <option value="73">73</option>
                        <option value="74">74</option>
                        <option value="75">75</option>
                        <option value="76">76</option>
                        <option value="77">77</option>
                        <option value="78">78</option>
                        <option value="79">79</option>
                        <option value="80">80</option>
                    </select>
                </div>
                
                <div class="search">
                    <h3>Î ÎµÏÎ¹Î¿ÏÎ®:</h3>
                    
                    <select name="location">
                        <option value="0" selected="selected">ÎÏÏ ÏÎ±Î½ÏÎ¿Ï</option>
                        <option value="117">ÎÏÏÎ±</option><option value="46">ÎÏÎ³Î¿Ï</option><option value="109">ÎÎ³Î¹Î¿Ï ÎÎ¹ÎºÏÎ»Î±Î¿Ï</option><option value="98">ÎÎ¼ÏÎ¹ÏÏÎ±</option><option value="146">ÎÎ´ÎµÏÏÎ±</option><option value="149">ÎÏÎ³Î¿ÏÏÏÎ»Î¹</option><option value="160">ÎÏÏÏÏÏÏÏÎ³Î¿Ï</option><option value="35">ÎÎ³ÏÎ¯Î½Î¹Î¿</option><option value="2">ÎÎ¸Î®Î½Î±</option><option value="113">ÎÎ»ÎµÎ¾Î±Î½Î´ÏÎ¿ÏÏÎ¿Î»Î·</option><option value="159">ÎÎ¼ÏÎ¹Î»Î¿ÏÎ¯Î±</option><option value="137">ÎÏÎ»Î¿Ï</option><option value="102">ÎÎ­ÏÎ¿Î¹Î±</option><option value="143">ÎÏÎµÎ²ÎµÎ½Î¬</option><option value="112">ÎÏÎ¬Î¼Î±</option><option value="37">ÎÏÎ­ÏÏÎ¹Î±</option><option value="133">ÎÏÎ¼Î¿ÏÏÎ¿Î»Î·</option><option value="157">ÎÎ»ÎµÏÏÎ¯Î½Î±</option><option value="124">ÎÎ¬ÎºÏÎ½Î¸Î¿Ï</option><option value="110">ÎÏÎ¬ÎºÎ»ÎµÎ¹Î¿</option><option value="120">ÎÎ³Î¿ÏÎ¼ÎµÎ½Î¯ÏÏÎ±</option><option value="154">ÎÎ®Î²Î±</option><option value="107">ÎÎµÏÏÎ±Î»Î¿Î½Î¯ÎºÎ·</option><option value="1">ÎÏÎ¬Î½Î½Î¹Î½Î±</option><option value="155">ÎÎµÏÎ¬ÏÎµÏÏÎ±</option><option value="130">ÎÏÏÎ¹Î½Î¸Î¿Ï</option><option value="26">ÎÏÏÏÎ¿Ï</option><option value="121">ÎÎ­ÏÎºÏÏÎ±</option><option value="97">ÎÎ±ÏÏÎµÎ½Î®ÏÎ¹</option><option value="135">ÎÎ±ÏÎ´Î¯ÏÏÎ±</option><option value="144">ÎÎ±ÏÏÎ¿ÏÎ¹Î¬</option><option value="105">ÎÎ±ÏÎµÏÎ¯Î½Î·</option><option value="114">ÎÎ±Î²Î¬Î»Î±</option><option value="132">ÎÎ±Î»Î±Î¼Î¬ÏÎ±</option><option value="122">ÎÎµÏÎ±Î»Î»Î¿Î½Î¹Î¬</option><option value="161">ÎÎ¹Î¬ÏÎ¿</option><option value="103">ÎÎ¹Î»ÎºÎ¯Ï</option><option value="145">ÎÎ¿Î¶Î¬Î½Î·</option><option value="115">ÎÎ¿Î¼Î¿ÏÎ·Î½Î®</option><option value="136">ÎÎ¬ÏÎ¹ÏÎ±</option><option value="126">ÎÎ­ÏÎ²Î¿Ï</option><option value="99">ÎÎ±Î¼Î¯Î±</option><option value="123">ÎÎµÏÎºÎ¬Î´Î±</option><option value="153">ÎÎ·Î¾Î¿ÏÏÎ¹</option><option value="100">ÎÎ¹Î²Î±Î´ÎµÎ¹Î¬</option><option value="147">ÎÏÏÎ¹Î»Î®Î½Î·</option><option value="158">ÎÎ­ÏÏÎ¿Î²Î¿</option><option value="140">ÎÎµÏÎ¿Î»ÏÎ³Î³Î¹</option><option value="152">ÎÎ¬Î¿ÏÏÎ±</option><option value="129">ÎÎ±ÏÏÎ»Î¹Î¿</option><option value="116">ÎÎ¬Î½Î¸Î·</option><option value="151">ÎÏÎµÏÏÎ¹Î¬Î´Î±</option><option value="119">Î ÏÎ­Î²ÎµÎ¶Î±</option><option value="141">Î ÏÏÎ³Î¿Ï</option><option value="139">Î Î¬ÏÏÎ±</option><option value="148">Î ÎµÎ¹ÏÎ±Î¹Î¬Ï</option><option value="101">Î Î¿Î»ÏÎ³ÏÏÎ¿Ï</option><option value="134">Î¡ÏÎ´Î¿Ï</option><option value="111">Î¡Î­Î¸ÏÎ¼Î½Î¿</option><option value="131">Î£ÏÎ¬ÏÏÎ·</option><option value="156">Î£ÏÎ­ÏÏÎµÏ</option><option value="150">Î£ÏÏÎ¿Ï</option><option value="127">Î£Î¬Î¼Î¿Ï</option><option value="106">Î£Î­ÏÏÎµÏ</option><option value="44">Î£ÎºÏÏÎ¿Ï</option><option value="128">Î¤ÏÎ¯ÏÎ¿Î»Î·</option><option value="138">Î¤ÏÎ¯ÎºÎ±Î»Î±</option><option value="142">Î¦Î»ÏÏÎ¹Î½Î±</option><option value="125">Î§Î¯Î¿Ï</option><option value="96">Î§Î±Î»ÎºÎ¯Î´Î±</option><option value="11">Î§Î±Î½Î¹Î¬</option>
                    </select>
                </div>

                <div class="search">
                    <h3>Î£ÎµÎ¾Î¿ÏÎ±Î»Î¹ÎºÎ­Ï ÏÏÎ¿ÏÎ¹Î¼Î®ÏÎµÎ¹Ï:</h3>
                    
                    <select name="orientation">
                        <option value="0">ÎÏÎ¹Î´Î®ÏÎ¿ÏÎµ</option>
                        <option value="straight">Straight</option>
                        <option value="bi">Bisexual</option>
                        <option value="gay">Gay/Lesbian</option>
                    </select>
                </div>
                
                <div><input type="submit" value="Î¨Î¬Î¾Îµ!" class="submit" /></div>
            </form>
            </div>
            <i class="bl"></i>
            <i class="br"></i>
        </div><?php
        }
    }
?>
