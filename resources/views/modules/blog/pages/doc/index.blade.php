@extends('blog::layouts.front')

@section('content')
<div class="container">
  <div class="content">
    <h1>Container</h1>
    <h3>default container max-width : 1170px</h3>
    <code>div.container</code>
    <h3>container-md max-width : 960px</h3>
    <code>div.container-md</code>
    <h3>container-full max-width : 100%</h3>
    <code>div.container-full</code>
  </div>
  <div class="content single-kol">
    <h1>Griding neat</h1>
    <h3>1 column, gutter default (20px)</h3>
    <div class="row">
      <div class="kol1">
        <span>.kol1</span>
      </div>
      <div class="kol11">
        <span>.kol11</span>
      </div>
    </div>
    <div class="row">
      <div class="kol2">
        <span>.kol2</span>
      </div>
      <div class="kol10">
        <span>.kol10</span>
      </div>
    </div>
    <div class="row">
      <div class="kol3">
        <span>.kol3</span>
      </div>
      <div class="kol9">
        <span>.kol9</span>
      </div>
    </div>
    <div class="row">
      <div class="kol4">
        <span>.kol4</span>
      </div>
      <div class="kol8">
        <span>.kol8</span>
      </div>
    </div>
    <div class="row">
      <div class="kol5">
        <span>.kol5</span>
      </div>
      <div class="kol7">
        <span>.kol7</span>
      </div>
    </div>
    <div class="row">
      <div class="kol6">
        <span>.kol6</span>
      </div>
      <div class="kol6">
        <span>.kol6</span>
      </div>
    </div>
    <div class="row">
      <div class="kol7">
        <span>.kol7</span>
      </div>
      <div class="kol5">
        <span>.kol5</span>
      </div>
    </div>
    <div class="row">
      <div class="kol8">
        <span>.kol8</span>
      </div>
      <div class="kol4">
        <span>.kol4</span>
      </div>
    </div>
    <div class="row">
      <div class="kol9">
        <span>.kol9</span>
      </div>
      <div class="kol3">
        <span>.kol3</span>
      </div>
    </div>
    <div class="row">
      <div class="kol10">
        <span>.kol10</span>
      </div>
      <div class="kol2">
        <span>.kol2</span>
      </div>
    </div>
    <div class="row">
      <div class="kol11">
        <span>.kol11</span>
      </div>
      <div class="kol1">
        <span>.kol1</span>
      </div>
    </div>
    <div class="row">
      <div class="kol12">
        <span>.kol12</span>
      </div>
    </div>
  </div>
  <div class="content">
    <h3>usage</h3>
    <code>div.row > div.kol12</code>
    <hr>
    <h3>1 colomn, gutter custom 20px</h3>
      <div class="the-row">
        <div class="cst-1-col">
          <span>.cst-1-col</span>
        </div>
      </div>
    <h3>2 colomn, gutter custom 20px</h3>
      <div class="the-row">
        <div class="cst-2-col">
          <span>.cst-2-col</span>
        </div>
        <div class="cst-2-col">
          <span>.cst-2-col</span>
        </div>
      </div>
    <h3>3 column, gutter custom (20px)</h3>
      <div class="the-row">
        <div class="cst-3-col">
          <span>.cst-3-col</span>
        </div>
        <div class="cst-3-col">
          <span>.cst-3-col</span>
        </div>
        <div class="cst-3-col">
          <span>.cst-3-col</span>
        </div>
      </div>
      <h3>4 column, gutter custom (20px)</h3>
        <div class="the-row">
          <div class="cst-4-col">
            <span>.cst-4-col</span>
          </div>
          <div class="cst-4-col">
            <span>.cst-4-col</span>
          </div>
          <div class="cst-4-col">
            <span>.cst-4-col</span>
          </div>
          <div class="cst-4-col">
            <span>.cst-4-col</span>
          </div>
        </div>
        <h3>5 column, gutter custom (20px)</h3>
          <div class="the-row">
            <div class="cst-5-col">
              <span>.cst-5-col</span>
            </div>
            <div class="cst-5-col">
              <span>.cst-5-col</span>
            </div>
            <div class="cst-5-col">
              <span>.cst-5-col</span>
            </div>
            <div class="cst-5-col">
              <span>.cst-5-col</span>
            </div>
            <div class="cst-5-col">
              <span>.cst-5-col</span>
            </div>
          </div>
          <h3>6 column, gutter custom (20px)</h3>
            <div class="the-row">
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
              <div class="cst-6-col">
                <span>.cst-6-col</span>
              </div>
            </div>
            <h3>7 column, gutter custom (20px)</h3>
              <div class="the-row">
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
                <div class="cst-7-col">
                  <span>.cst-7-col</span>
                </div>
              </div>
              <h3>8 column, gutter custom (20px)</h3>
                <div class="the-row">
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                  <div class="cst-8-col">
                    <span>.cst-8-col</span>
                  </div>
                </div>
                <h3>9 column, gutter custom (20px)</h3>
                  <div class="the-row">
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                    <div class="cst-9-col">
                      <span>.cst-9-col</span>
                    </div>
                  </div>
                <h3>10 colomn, gutter custom 20px</h3>
                  <div class="the-row">
                    <div class="cst-10-col">
                      <span>.cst-10-col</span>
                    </div>
                  </div>
                <h3>11 colomn, gutter custom 20px</h3>
                  <div class="the-row">
                    <div class="cst-11-col">
                      <span>.cst-11-col</span>
                    </div>
                  </div>
                <h3>12 colomn, gutter custom 20px</h3>
                  <div class="the-row">
                    <div class="cst-12-col">
                      <span>.cst-12-col</span>
                    </div>
                  </div>
                <h3>usage</h3>
                <code>div.the-row > div.cst-12-col, div.cst-12-col, div.cst-12-col, div.cst-12-col</code>
                <hr>
  </div>
  <div class="content">
    <h1>Paragraph</h1>
    <p>Nostra magnam inceptos in a? Torquent quis consectetuer ut repellat, tellus rutrum dolorum leo dapibus! Hic hac rutrum, nobis cras dignissim. Ratione risus nisl! Nec feugiat varius tenetur duis, mauris, euismod! Condimentum fermentum laboriosam adipiscing. Ligula, fugiat sem eius turpis laborum reiciendis, aliquet cillum congue explicabo, congue mattis sunt culpa.</p>
    <p>Accusamus lacinia euismod quibusdam primis magni eiusmod diamlorem, velit officia? Ipsa recusandae placeat libero sapien, proident tellus malesuada repellat penatibus rerum repellendus eros varius ridiculus adipiscing. Luctus placerat? Nihil nobis netus lacus delectus quam hic veritatis, in facilisis, mollis sunt, massa metus natoque, euismod, adipiscing. Sed placerat sollicitudin quia ultrices.</p>
  </div>
  <div class="content">
    <p><strong>Headings</strong></p>
    <h1>Header one</h1>
    <h2>Header two</h2>
    <h3>Header three</h3>
    <h4>Header four</h4>
    <h5>Header five</h5>
    <h6>Header six</h6>
    <h2>Blockquotes</h2>
    <p>Single line blockquote:</p>
    <blockquote><p>Stay hungry. Stay foolish.</p></blockquote>
    <p>Multi line blockquote with a cite reference:</p>
    <blockquote cite="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/blockquote">
      <p>The <strong>HTML <code>&lt;blockquote&gt;</code> Element</strong> (or <em>HTML Block Quotation Element</em>) indicates that the enclosed text is an extended quotation. Usually, this is rendered visually by indentation (see <a href="https://developer.mozilla.org/en-US/docs/HTML/Element/blockquote#Notes">Notes</a> for how to change it). A URL for the source of the quotation may be given using the <strong>cite</strong> attribute, while a text representation of the source can be given using the <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/cite" title="The HTML Citation Element &lt;cite&gt; represents a reference to a creative work. It must include the title of a work or a URL reference, which may be in an abbreviated form according to the conventions used for the addition of citation metadata."><code>&lt;cite&gt;</code></a> element.</p>
    </blockquote>
    <p><cite>multiple contributors</cite> &#8211; MDN HTML element reference &#8211; blockquote</p>
    <h2>Tables</h2>
    <table>
      <thead>
        <tr>
          <th>Employee</th>
          <th>Salary</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><a href="http://example.org/">John Doe</a></th>
          <td>$1</td>
          <td>Because that&#8217;s all Steve Jobs needed for a salary.</td>
        </tr>
        <tr>
          <th><a href="http://example.org/">Jane Doe</a></th>
          <td>$100K</td>
          <td>For all the blogging she does.</td>
        </tr>
        <tr>
          <th><a href="http://example.org/">Fred Bloggs</a></th>
          <td>$100M</td>
          <td>Pictures are worth a thousand words, right? So Jane x 1,000.</td>
        </tr>
        <tr>
          <th><a href="http://example.org/">Jane Bloggs</a></th>
          <td>$100B</td>
          <td>With hair like that?! Enough said&#8230;</td>
        </tr>
      </tbody>
    </table>
    <h2>Definition Lists</h2>
    <dl>
      <dt>Definition List Title</dt>
      <dd>Definition list division.</dd>
      <dt>Startup</dt>
      <dd>A startup company or startup is a company or temporary organization designed to search for a repeatable and scalable business model.</dd>
      <dt>#dowork</dt>
      <dd>Coined by Rob Dyrdek and his personal body guard Christopher &#8220;Big Black&#8221; Boykins, &#8220;Do Work&#8221; works as a self motivator, to motivating your friends.</dd>
      <dt>Do It Live</dt>
      <dd>I&#8217;ll let Bill O&#8217;Reilly will <a title="We'll Do It Live" href="https://www.youtube.com/watch?v=O_HyZ5aW76c">explain</a> this one.</dd>
    </dl>
    <h2>Unordered Lists (Nested)</h2>
    <ul>
      <li>List item one
        <ul>
          <li>List item one
            <ul>
              <li>List item one</li>
              <li>List item two</li>
              <li>List item three</li>
              <li>List item four</li>
            </ul>
          </li>
          <li>List item two</li>
          <li>List item three</li>
          <li>List item four</li>
        </ul>
      </li>
      <li>List item two</li>
      <li>List item three</li>
      <li>List item four</li>
    </ul>
    <h2>Ordered List (Nested)</h2>
    <ol start="8">
      <li>List item one -start at 8
        <ol>
          <li>List item one
            <ol reversed="reversed">
              <li>List item one -reversed attribute</li>
              <li>List item two</li>
              <li>List item three</li>
              <li>List item four</li>
            </ol>
          </li>
          <li>List item two</li>
          <li>List item three</li>
          <li>List item four</li>
        </ol>
      </li>
      <li>List item two</li>
      <li>List item three</li>
      <li>List item four</li>
    </ol>
    <h2>HTML Tags</h2>
    <p>These supported tags come from the WordPress.com code <a title="Code" href="http://en.support.wordpress.com/code/">FAQ</a>.</p>
    <p><strong>Address Tag</strong></p>
    <address>1 Infinite Loop<br />
      Cupertino, CA 95014<br />
      United States</address>
      <p><strong>Anchor Tag (aka. Link)</strong></p>
      <p>This is an example of a <a title="Apple" href="http://apple.com">link</a>.</p>
      <p><strong>Abbreviation Tag</strong></p>
      <p>The abbreviation <abbr title="Seriously">srsly</abbr> stands for &#8220;seriously&#8221;.</p>
      <p><strong>Acronym Tag (<em>deprecated in HTML5</em>)</strong></p>
      <p>The acronym <acronym title="For The Win">ftw</acronym> stands for &#8220;for the win&#8221;.</p>
      <p><strong>Big Tag</strong> (<em>deprecated in HTML5</em>)</p>
      <p>These tests are a <big>big</big> deal, but this tag is no longer supported in HTML5.</p>
      <p><strong>Cite Tag</strong></p>
      <p>&#8220;Code is poetry.&#8221; &#8212;<cite>Automattic</cite></p>
      <p><strong>Code Tag</strong></p>
      <p>This tag styles blocks of code.<br />
        <code>.post-title {<br />
          margin: 0 0 5px;<br />
          font-weight: bold;<br />
          font-size: 38px;<br />
          line-height: 1.2;<br />
          and here's a line of some really, really, really, really long text, just to see how it is handled and to find out how it overflows;<br />
        }</code><br />
        You will learn later on in these tests that word-wrap: break-word;will be your best friend.</p>
        <p><strong>Delete Tag</strong></p>
        <p>This tag will let you <del cite="deleted it">strike out text</del>, but this tag is <em>recommended</em> supported in HTML5 (use the <code>&lt;s&gt;</code> instead).</p>
        <p><strong>Emphasize Tag</strong></p>
        <p>The emphasize tag should <em>italicize</em> <i>text</i>.</p>
        <p><strong>Horizontal Rule Tag</strong></p>
        <hr />
        <p>This sentence is following a <code>&lt;hr /&gt;</code> tag.</p>
        <p><strong>Insert Tag</strong></p>
        <p>This tag should denote <ins cite="inserted it">inserted</ins> text.</p>
        <p><strong>Keyboard Tag</strong></p>
        <p>This scarcely known tag emulates <kbd>keyboard text</kbd>, which is usually styled like the <code>&lt;code&gt;</code> tag.</p>
        <p><strong>Preformatted Tag</strong></p>
        <p>This tag is for preserving whitespace as typed, such as in poetry or ASCII art.</p>
        <pre>
          <h2>The Road Not Taken</h2>
          <cite>Robert Frost</cite>


          Two roads diverged in a yellow wood,
          And sorry I could not travel both          (\_/)
          And be one traveler, long I stood         (='.'=)
          And looked down one as far as I could     (")_(")
          To where it bent in the undergrowth;

          Then took the other, as just as fair,
          And having perhaps the better claim,          |\_/|
          Because it was grassy and wanted wear;       / @ @ \
          Though as for that the passing there        ( > º < )
          Had worn them really about the same,         `>>x<<´
          /  O  \
          And both that morning equally lay
          In leaves no step had trodden black.
          Oh, I kept the first for another day!
          Yet knowing how way leads on to way,
          I doubted if I should ever come back.

          I shall be telling this with a sigh
          Somewhere ages and ages hence:
          Two roads diverged in a wood, and I—
          I took the one less traveled by,
          And that has made all the difference.


          and here's a line of some really, really, really, really long text, just to see how it is handled and to find out how it overflows;
        </pre>
        <p><strong>Quote Tag</strong> for short, inline quotes</p>
        <p><q>Developers, developers, developers...</q> --Steve Ballmer</p>
        <p><strong>Strike Tag</strong> (<em>deprecated in HTML5</em>) and <strong>S Tag</strong></p>
        <p>This tag shows <strike>strike-through</strike> <s>text</s>.</p>
        <p><strong>Small Tag</strong></p>
        <p>This tag shows <small>smaller<small> text.</small></small></p>
        <p><strong>Strong Tag</strong></p>
        <p>This tag shows <strong>bold<strong> text.</strong></strong></p>
        <p><strong>Subscript Tag</strong></p>
        <p>Getting our science styling on with H<sub>2</sub>O, which should push the "2" down.</p>
        <p><strong>Superscript Tag</strong></p>
        <p>Still sticking with science and Isaac Newton's E = MC<sup>2</sup>, which should lift the 2 up.</p>
        <p><strong>Teletype Tag </strong>(<em>obsolete in HTML5</em>)</p>
        <p>This rarely used tag emulates <tt>teletype text</tt>, which is usually styled like the <code>&lt;code&gt;</code> tag.</p>
        <p><strong>Underline Tag</strong> <em>deprecated in HTML 4, re-introduced in HTML5 with other semantics</em></p>
        <p>This tag shows <u>underlined text</u>.</p>
        <p><strong>Variable Tag</strong></p>
        <p>This allows you to denote <var>variables</var>.</p>
      </div>
    </div>
    @stop
