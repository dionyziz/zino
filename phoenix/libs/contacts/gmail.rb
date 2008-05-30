# GMail contacts parser
require 'mechanize'
#require 'rubygems'

# Unicode workaround -- not working, currently
#$KCODE='u'
#require 'jcode'

def getlogin
	$stderr.print "user: "; @username = gets.chomp
	$stderr.print "pass: "; @passwd = gets.chomp 
	if @username.length < 1 || @passwd.length < 1
		$stderr.print "Incorrect userdata input!\n"
		exit 1
	end
end

def login
	@agent = WWW::Mechanize.new
	page = @agent.get(LoginURL)
	form = page.forms.first #name('gaia_loginform').first
	form.Email = @username
	form.Passwd = @passwd
	a = ''
begin
	page = @agent.submit(form, form.buttons.first)
	page = @agent.get page.search("//meta").first.attributes['href'].gsub(/'/,'') #META-Redirect
	#link = page.links.text(/Contacts/)
 	page = @agent.get(ContactsURL)
	a = page.body[/nvp_bu_sc.*\Z/m]
	a = a[/<table.*\Z/m]
	a = a[a.index('>')+2..a.index('</table>')-1]

rescue StandardError => boom
	$stderr.print "Login error!\n" + boom #+ "\n" + page.body
	exit 2
end
	lines = a.split('<tr>')
	lines.each do |x|
	    if x=~/<td>/ then
		fields = x.split('<td>')
		name = fields[2].gsub(/\A.*<b>\s*(.*)\s*<\/b>.*\Z/m,'\1')
		email = fields[3].gsub(/\s*(.*)\s*<\/td>.*\Z/m, '\1').gsub(/(\s+&nbsp;\s+)/, '').gsub(/(\r?\n)+/m, ' ').gsub(/(\t)/m, ' ')
		print "#{name}\t#{email}\n"
	    end
	end
#	puts a #page.body

end

LoginURL = 'https://www.google.com/accounts/ServiceLogin?service=mail&passive=true&rm=false&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fh%2Fposshk2wzcz6%2F%3Fnsr%3D0%26ui%3Dhtml&ltmpl=default&ltmplcache=2'
ContactsURL = '?v=cl&pnl=a'

#getlogin
user = gets.chomp;
pass = gets.chomp;
$stderr.print user, ':', pass , "\n"
#$stderr.print "Logging in #{@username}...\n"
#login
exit 20

