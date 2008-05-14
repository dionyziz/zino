# GMail contacts parser
require 'mechanize'
#require 'rubygems'

def getlogin
	@username = gets.chomp
	@passwd = gets.chomp
	if @username.length < 1 || @passwd.length < 1
		print "Please enter username and password, seperated by newline\n"
		exit -1
	end
end

def login
	@agent = WWW::Mechanize.new
	page = @agent.get(LoginURL)
	form = page.forms.first #name('gaia_loginform').first
	form.Email = @username
	form.Passwd = @passwd
	page = @agent.submit(form, form.buttons.first)
# META-Redirect
	page = @agent.get page.search("//meta").first.attributes['href'].gsub(/'/,'')

#	link = page.links.text(/Contacts/)
 	page = @agent.get(ContactsURL)

	a = page.body[/nvp_bu_sc.*$/]
	a = a[/<table.*$/]
	a = a[a.index('>')+2..a.index('</table>')-1]
	lines = a.split('<tr>')
	lines.each do |x|
	    if x=~/<td>/ then
		fields = x.split('<td>')
		print fields[2].gsub(/<b>(.*)<\/b>/,'\1'), "\n", fields[3][0..fields[3].index('</td>')-1], "\n"
	    end
	end
#	puts a #page.body
end

LoginURL = 'https://www.google.com/accounts/ServiceLogin?service=mail&passive=true&rm=false&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fh%2Fposshk2wzcz6%2F%3Fnsr%3D0%26ui%3Dhtml&ltmpl=default&ltmplcache=2'
ContactsURL = '?v=cl&pnl=a'

getlogin
#puts "Hello #{@username}!"
login

