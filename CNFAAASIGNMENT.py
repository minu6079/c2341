w = input('\nEnter expression: ')
righthand=''
lefthand=''

righthand=w.split(' -> ')[0]
lefthand=w.split(' -> ')[1]
last= lefthand[-1]

a=0
while True:
    right = righthand
    righthand = lefthand[0]
    lefthand = lefthand[1:]

  
    print ( "<" + right + ">" + "->" + "<" + righthand + ">" + " " + "<" + lefthand + ">")
    if(righthand.isupper()==False) :
        print("<" + righthand + ">" + " -> " + "<" + righthand + ">")



    if righthand==last:
        break

    righthand=lefthand
    lefthand = lefthand
    a+=1
