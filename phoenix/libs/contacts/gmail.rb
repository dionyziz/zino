# GMail contacts parser
require 'mechanize'
#require 'rubygems'

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
begin
	page = @agent.submit(form, form.buttons.first)
	page = @agent.get page.search("//meta").first.attributes['href'].gsub(/'/,'') #META-Redirect
	#link = page.links.text(/Contacts/)
 	page = @agent.get(ContactsURL)

rescue StandardError => boom
	$stderr.print "Login error!\n" + boom
	exit 2
end
	a = page.body[/nvp_bu_sc.*$/]
	a = a[/<table.*$/]
	a = a[a.index('>')+2..a.index('</table>')-1]
	lines = a.split('<tr>')
	lines.each do |x|
	    if x=~/<td>/ then
		fields = x.split('<td>')
		name = fields[2].gsub(/\A.*<b>\s*(.*)\s*<\/b>.*\Z/,'\1')
		email = fields[3].gsub(/\s*(\S*)\s*&nbsp;\s*<\/td>.*\Z/, '\1')
		print "#{name}\t#{email}\n"
	    end
	end
#	puts a #page.body

end

LoginURL = 'https://www.google.com/accounts/ServiceLogin?service=mail&passive=true&rm=false&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fh%2Fposshk2wzcz6%2F%3Fnsr%3D0%26ui%3Dhtml&ltmpl=default&ltmplcache=2'
ContactsURL = '?v=cl&pnl=a'

getlogin
$stderr.print "Logging in #{@username}...\n"
login
exit 0

