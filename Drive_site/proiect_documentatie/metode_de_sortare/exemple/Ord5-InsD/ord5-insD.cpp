#include <iostream>

using namespace std;

int main()
{
    int a[100], n, i, j, y;
    cout << "n = "; cin >> n;
    for(i = 0; i < n; i++)
    {
        cout << "a[" << i+1 << "] = ";
        cin >> y;
        j=i-1;
        while ((j>=0) && (a[j]>y))
        {
            a[j+1]=a[j];
            j--;
        }
        a[j+1]=y;
   }


    cout << endl;
    for(i = 0; i < n; i++)
        cout << "a[" << i+1 << "] = " << a[i] << endl;
    return 0;
}
