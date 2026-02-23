from django.shortcuts import render,redirect
from django.contrib import messages
from django.conf import settings
from .form import Contactfrom
 
def contact(request):
    if request.method=='POST':
        form = ContactForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, 'Your message has been sent successfully!')
            return redirect('home')
    else:
        form = ContactForm()
    return render(request, 'contact.html', {'form': form})