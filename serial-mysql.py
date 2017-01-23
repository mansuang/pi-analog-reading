import serial
import time
import MySQLdb
import ConfigParser


config = ConfigParser.ConfigParser()
config.readfp(open('./config.ini'))

# channel = 0

db = MySQLdb.connect(host=config.get('MYSQL','host'),       # your host, usually localhost
                     user=config.get('MYSQL','username'),   # your username
                     passwd=config.get('MYSQL','password'),  # your password
                     db=config.get('MYSQL','db'))        # name of the data base

cur = db.cursor()

def log_data(name, val):
    try:
		cur.execute("INSERT INTO `data` (`name`, `current_value`, `updated_at`) VALUES ('%s', '%s', NOW()) ON DUPLICATE KEY UPDATE  current_value='%s', updated_at=NOW()" % (name, val, val))
		db.commit()
    except MySQLdb.Error, e:
		print "MySQL Error %d: %s" % (e.args[0],e.args[1])
		db.rollback()


def read_data():
	serialport = serial.Serial("/dev/ttyAMA0", 9600, timeout=0.5)
	serialport.write("status\r\n")
	# serialport.write("gpio1 0")
	response = serialport.readlines()

	names = ['ec','temp','ph','water_level','gpio1','gpio2','gpio3','gpio4']
	# response = ['\r\n', '3.98,23.56,5.50,0,0,0,1,1\r\n'];
	if len(response) > 1:
		s = response[1]
		s = s.replace('\r\n','')
		values = s.split(',')	

		if len(values) > 1 :	
			for i, name in enumerate(names):
				log_data(name, values[i])
				print name,'=',values[i],
		else:
			print s
	else:
		print "no response"

while True:
	read_data()
	time.sleep(1)