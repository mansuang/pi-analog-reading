import serial
serialport = serial.Serial("/dev/ttyAMA0", 9600, timeout=0.5)
serialport.write("status\r\n")
#erialport.write("gpio1 0")
response = serialport.readlines()
#response = ['\r\n', '3.97,23.56,5.50,0,0,0,1,1\r\n'];
print response
