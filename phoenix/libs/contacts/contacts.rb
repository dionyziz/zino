# Contacts parser
require 'rubygems'
require 'blackbook'

$stderr.print "user: "; username = gets.chomp
$stderr.print "pass: "; passwd = gets.chomp 
if username.length < 1 || passwd.length < 1
	$stderr.print "Invalid input!\n"
	exit 1
end

#$stderr.print username, ':', passwd, "\n"
$stderr.print "Logging in #{username}...\n"

begin
contacts = Blackbook.get :username => username, :password => passwd
rescue Blackbook::BadCredentialsError
	$stderr.print "Wrong username/password."
	exit 2
rescue ArgumentError
	$stderr.print "Invalid email."
	exit 3
end
contacts.each do |contact|
	print contact[:name] + "\t" + contact[:email] + "\n"
	end

